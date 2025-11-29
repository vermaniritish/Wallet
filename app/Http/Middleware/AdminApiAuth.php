<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\API\UsersTokens;
use App\Models\Admin\Admins;

class AdminApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized. Token missing.'], 401);
        }

        $userToken = UsersTokens::where('token', $token)->first();

        if (!$userToken) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        // Check expiry
        // if ($userToken->expire_on < now()) {
        //     return response()->json(['message' => 'Token expired.'], 401);
        // }

        // Get admin
        $admin = Admins::find($userToken->user_id);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found.'], 404);
        }

        // Attach admin to request
        $request->merge(['admin' => $admin]);

        return $next($request);
    }
}
