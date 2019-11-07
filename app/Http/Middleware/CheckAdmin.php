<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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

        // The user must be an admin user
        switch ($user->role) {
            case 'admin':            
            case 'partner':
                break;

            default:
                return response([
                    'status' => 'false',
                    'message' => 'Forbidden: Insufficient privileges',
                    'hint'    => 'You must be logged in as an admin officer',
                ], 403);
                break;
        }

        return $next($request);
    }
}
