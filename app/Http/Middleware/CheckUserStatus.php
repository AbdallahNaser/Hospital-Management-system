<?php

namespace App\Http\Middleware;

use Closure;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckUserStatus
 */
class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (getSettingValue('email_verified') && ! getLogInUser()->email_verified_at) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Please verify your email.'], 403);
                }
                Auth::guard('web')->logout();
                Flash::error('Please verify your email.');

                return redirect()->to('login');
            }
            if (! getLogInUser()->status) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Your Account is currently disabled, please contact to administrator.'], 403);
                }
                Auth::guard('web')->logout();
                Flash::error('Your Account is currently disabled, please contact to administrator.');

                return redirect()->to('login');
            }
        }

        return $next($request);
    }
}
