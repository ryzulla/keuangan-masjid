<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleEnabled
{
    /**
     * Blokir akses (404) bila modul dinonaktifkan superadmin.
     * Dipakai: ->middleware('module:perumahan') / 'module:dkm'
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        abort_unless(Setting::moduleEnabled($module), 404);

        return $next($request);
    }
}
