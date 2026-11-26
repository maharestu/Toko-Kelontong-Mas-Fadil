<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request and verify the authenticated user has one of the allowed roles.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userRole = $user->role instanceof UserRole ? $user->role->value : $user->role;

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Akses ditolak. Fitur ini hanya tersedia untuk Admin.');
        }

        return $next($request);
    }
}
