<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        if (!$request->user()) {
            return redirect('/auth/login');
        }

        if ($request->user()->role !== $role) {
            return redirect('/auth/login');
        }

        return $next($request);
    }
}
