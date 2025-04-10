<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Mapping dari role bahasa Inggris ke role enum DB (bahasa Indonesia)
        $roleMap = [
            'secretary' => 'sekretaris',
            'student' => 'siswa',
            'teacher' => 'guru',
        ];

        $userRole = $request->user()->role;
        $expectedRole = $roleMap[$role] ?? $role;

        if ($userRole !== $expectedRole) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
