<?php

namespace App\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        if(!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();

        if($user->role !== 'admin') {
            foreach ($roles as $role) {
                if ($user->role === $role) {
                    return $next($request);
                }
            }
            return response()->json([
                'message' => 'Unauthorized.'
            ], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
