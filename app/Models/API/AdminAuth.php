<?php

namespace App\Models\API;

use App\Models\Admin\AdminAuth as AdminAdminAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\FileSystem;
use App\Models\API\Admins;
use App\Libraries\General;
use App\Models\Admin\Settings;

class AdminAuth extends AdminAdminAuth
{
    public static function makeLoginSession(Request $request, $admin)
    {
        if (!$admin) {
            return null;
        }

        // Multi login handling
        if (Settings::get('client_multi_device_logins')) {

            if ($request->bearerToken()) {
                UsersTokens::where('token', trim($request->bearerToken()))
                    ->where('user_id', $admin->id)
                    ->delete();
            }

            if ($request->device_id) {
                UsersTokens::where('device_id', $request->device_id)
                    ->where('user_id', $admin->id)
                    ->delete();
            }

        } else {
            UsersTokens::where('user_id', $admin->id)->delete();
        }

        $expireMins = Settings::get('session_expires_in_minutes');
        $tokenString = General::hash(64);

        $token = UsersTokens::create([
            'user_id'     => $admin->id,
            'token'       => $tokenString,
            'device_id'   => $request->device_id ?? null,
            'device_type' => $request->device_type ?? 'mobile',
            'device_name' => $request->device_name ?? null,
            'fcm_token'   => $request->fcm_token ?? null,
            'expire_on'   => now()->addMinutes($expireMins)
        ]);

        Admins::modify($admin->id, ['last_login' => now()]);

        $admin->access = [
            'token_id' => $token->id,
            'token'    => $tokenString,
            'expires'  => $token->expire_on,
        ];

        return $admin;
    }
}