<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('description');
            $table->string('location', 255)->nullable()->after('content');
            $table->string('video_url', 500)->nullable()->after('location');
        });
    }
    public function down(): void {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['content', 'location', 'video_url']);
        });
    }
};
