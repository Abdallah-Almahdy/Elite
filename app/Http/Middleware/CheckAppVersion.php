<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $exactBlockedVersion = config('app_versions.exact_blocked_version');
        $minSupportedVersion = config('app_versions.min_supported_version');
        $maintenanceMode     = config('app_versions.maintenance_mode');
        $maintenanceMessage  = config('app_versions.maintenance_message');
        $blockedVersions     = config('app_versions.blocked_versions', []);

        // 1. maintenance_mode
        $maintenance = $request->header('maintenanceMode') ?: $request->query('maintenanceMode');
        if ($maintenanceMode  || $maintenance == true) {
            return response()->json([
                'status'  => 'maintenance',
                'message' => $maintenanceMessage ?? 'The system is under maintenance.'
            ], 503);
        }
        else{
            // 2. get client version
        $appVersion = $request->header('App-Version') ?: $request->query('app_version');

        if (empty($appVersion)) {
            $appVersion = '0.0.0'; // fallback version
        }

        // 3. exact_blocked_version
        if (!empty($exactBlockedVersion) && $appVersion === $exactBlockedVersion) {
            return response()->json([
                'status'                => 'blocked',
                'message'               => 'This app version is blocked. Please update to continue.',
                'exact_blocked_version' => $exactBlockedVersion
            ], 426);
        }

        // 4. blocked_versions
        if (!empty($blockedVersions) && is_array($blockedVersions) && in_array($appVersion, $blockedVersions)) {
            return response()->json([
                'status'           => 'blocked',
                'message'          => 'This app version is blocked. Please update to continue.',
                'blocked_version'  => $appVersion
            ], 426);
        }

        // 5. min_supported_version
        if (!empty($minSupportedVersion) && version_compare($appVersion, $minSupportedVersion, '<')) {
            return response()->json([
                'status'                => 'update_required',
                'message'               => 'This app version is no longer supported. Please update to the latest version.',
                'min_supported_version' => '3.5.1'
            ], 426);
        }

        // If all checks pass, proceed with the request
        return $next($request);
    }
        }


}
