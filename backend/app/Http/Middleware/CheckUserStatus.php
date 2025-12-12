<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->status !== 'Active') {
            Auth::logout();
            return response()->json([
                'message' => 'Your account has been deactivated or blocked.'
            ], 403);
        }

        return $next($request);
    }
}