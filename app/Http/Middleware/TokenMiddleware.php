<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->input('t');
        $allowed = config('invite.expected_tokens', []);

        if (! in_array($token, $allowed, true)) {
            return response()
                ->view('qr-only-404', [], 404);
        }

        return $next($request);
    }
}
