<?php

namespace App\Http\Middleware;

use Closure;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        // WE need  an authenticated user
        if (! $user) {
            return response([
                'message' => 'Unauthorised!',
                'status' => 'false'
            ], 401);
        }

        // The user must be a super-admin
        if ($user->role != 'admin') {
            return response([
                'status'  => 'false',
                'message' => 'Forbidden: Insufficient privileges',
                'hint'    => 'You must be logged in as a super-admin officer',
            ], 403);
        }

        return $next($request);
    }
}
