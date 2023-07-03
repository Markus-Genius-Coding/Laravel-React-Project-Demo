<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;

class RequestUserPermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $serviceNames)
    {
        $hasPermissions = false;
        try {
            if ($request->hasHeader('Authorization')) {
                $bearerToken = $request->header('Authorization');
            } else if ($request->has('access_token')) {
                $bearerToken = $request->access_token;
            } else {
                Log::debug('Authorization is missing!');
                return response()->json([], 403);
            }

            $serviceNames = explode('|', $serviceNames);

            $tokenId = (new Parser(new JoseEncoder()))->parse($bearerToken)->claims()->get('jti');
            $token = DB::table('oauth_access_tokens')
                ->where('id', $tokenId)
                ->where('revoked', false)
                ->first();

            if ($token == null) {
                Log::debug("Token not found");
                return response()->json([], 401);
            }

            Log::debug('token: ', [$token]);
            $user = User::where('id', '=', $token->user_id)->first();

            // Check user permissions
            if ($user != null && $user->id != null && strlen($user->id) == 36) {
                $hasPermissions = UserPermission::where('user_id', $user->id)
                    ->whereIn('role_id', UserRole::whereIn('name', $serviceNames)->pluck('id'))
                    ->exists();

                //if the user is superuser -> we will approve it anyway
                if (UserPermission::where('user_id', $user->id)->where('role_id', UserRole::where('name', 'Admin')->first()->id)->exists()) {
                    $hasPermissions = true;
                }
            }

            // Check if user token expired
            if ($token->expires_at < Carbon::now()->format('Y-m-d H:i:s')) {
                Log::debug("User Token expired");
                return response()->json(['code' => -1, 'msg' => "expired"], 401);
            }

        } catch (\Exception $e) {
            Log::error('Token validation error: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());

            return response()->json([], 500);
        }

        if ($user != null && $hasPermissions == true) {
            return $next($request);
        } else if ($user == null) {
            Log::error("No user found for received token, status: 401");
            return response()->json([], 401);
        } else if ($hasPermissions == false) {
            return response()->json([], 403);
        } else {
            return response()->json([], 400);
        }
    }
}
