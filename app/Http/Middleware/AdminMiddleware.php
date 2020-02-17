<?php

namespace App\Http\Middleware;
use App\Token;

use Closure;

class AdminMiddleware
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
        $token = $request->token;
        $tokens = Token::where('token', $token)->first();
        $role = $tokens->user->role;

        if($role !== 'admin') {
            return response()->json(['message' => 'forbidden user access'], 402);
        }

        return $next($request);
    }
}
