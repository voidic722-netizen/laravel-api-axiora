<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Replaces admin_middleware.js / teacher_middleware.js / student_middleware.js
 * (Issue #13: original `studentMiddleware` actually allowed all roles —
 * a single parameterized middleware is clearer).
 *
 * Usage: ->middleware('role:admin,lecturer')
 */
class RoleMiddleware
{
    /**
     * @var array<string, RoleEnum>
     */
    private const ROLE_MAP = [
        'admin'    => RoleEnum::ADMIN,
        'lecturer' => RoleEnum::LECTURER,
        'student'  => RoleEnum::STUDENT,
    ];

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        $allowed = array_map(
            fn (string $role) => self::ROLE_MAP[$role] ?? null,
            $roles,
        );

        if (!in_array($user->role, $allowed, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
