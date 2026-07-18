<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    private string $projectId;
    private string $serviceAccountPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id', '');
        $this->serviceAccountPath = storage_path('app/firebase-service-account.json');
    }

    public function isConfigured(): bool
    {
        return $this->projectId !== '' && file_exists($this->serviceAccountPath);
    }

    public function getAccessToken(): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
        $now = time();

        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'iss'   => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));

        $signaturePayload = "$header.$payload";
        openssl_sign($signaturePayload, $signature, $serviceAccount['private_key'], 'SHA256');
        $jwt = "$header.$payload." . base64_encode($signature);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('FCM: Failed to get access token', ['response' => $response->body()]);
        return null;
    }

    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): void
    {
        if (empty($tokens) || ! $this->isConfigured()) {
            return;
        }

        $accessToken = $this->getAccessToken();
        if (! $accessToken) {
            return;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:sendBatch";

        $messages = array_map(fn($token) => [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
            ],
            'data' => $data,
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'emergency_alerts',
                    'sound'      => 'default',
                    'priority'   => 'max',
                ],
            ],
            'apns' => [
                'headers' => ['apns-priority' => '10'],
                'payload' => [
                    'aps' => [
                        'sound'     => 'default',
                        'badge'     => 1,
                        'interruption-level' => 'critical',
                    ],
                ],
            ],
        ], $tokens);

        // FCM v1 batch limit is 500 messages per request
        $chunks = array_chunk($messages, 500);

        foreach ($chunks as $chunk) {
            try {
                Http::withToken($accessToken)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, ['messages' => $chunk]);
            } catch (\Exception $e) {
                Log::error('FCM: Failed to send batch', ['error' => $e->getMessage()]);
            }
        }
    }

    public function sendToResidents(string $title, string $body, array $residentIds, array $data = []): void
    {
        $tokens = FcmToken::whereIn('resident_id', $residentIds)
            ->pluck('token')
            ->toArray();

        $this->sendToTokens($tokens, $title, $body, $data);
    }

    public function sendEmergencyAlert(\App\Models\EmergencyAlert $alert): void
    {
        $blockLetter = substr($alert->block_code, 0, 1);

        $residentIds = Resident::whereHas('currentAssignments.houseBlock', function ($q) use ($blockLetter) {
            $q->where('block_letter', $blockLetter);
        })
        ->where('is_active', true)
        ->pluck('id')
        ->toArray();

        $adminIds = User::where('role', 'super_admin')
            ->orWhere('role', 'admin')
            ->pluck('id')
            ->toArray();

        $allIds = array_unique(array_merge($residentIds, $adminIds));

        $title = '⚠️ DARURAT';
        $body = "Blok {$alert->block_code}: {$alert->message}";
        $data = [
            'type'       => 'emergency',
            'alert_id'   => (string) $alert->id,
            'block_code' => $alert->block_code,
            'message'    => $alert->message,
        ];

        $this->sendToResidents($title, $body, $allIds, $data);
    }
}
