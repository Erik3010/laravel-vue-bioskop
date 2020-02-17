<?php

namespace App\Http\Middleware;
use App\Token;

use Closure;

class AuthMiddleware
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
        if(!$tokens) {
            return response()->json(['message' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
