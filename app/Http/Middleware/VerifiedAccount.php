<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class VerifiedAccount
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass verification for specific routes
        if (Str::is('api/users/chat/PDF', $request->path())) {
            return $next($request);
        }

        try {
            $user = Auth::guard('api')->user();

            // Check if the user's email is verified
            if (is_null($user->email_verified_at)) {
                return $this->returnError('403', "This Account is Not Verified Yet.");
            }
        } catch (\Throwable $e) {
            Log::error('Verification Middleware Error: ' . $e->getMessage());
            return $this->returnError('500', "Server Error: Please contact support.");
        }

        return $next($request);
    }
}
