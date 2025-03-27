<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user()->role->name !== $role) {
            return response()->json(['message' => 'Không có quyền truy cập'], 403);
        }
        return $next($request);
    }
}
