<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate extends Middleware
{
    // Override handle method
    public function handle($request, Closure $next, ...$guards) {
        if($this->authenticate($request, $guards) === 'authentication_error') {
            return response()->json(['error' => 'unauthorized'], 400);
        }
        return $next($request);
    }

    // Override authentication method
    public function authenticate($request, array $guards) {
        if(empty($guards)) {
            $guards = [null];
        }
        foreach($guards as $guard) {
            if($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        return 'authentication_error';
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
