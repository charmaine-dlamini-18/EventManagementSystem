<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 *
 * Controls access to routes based on user roles.
 * Ensures that only authenticated users with the
 * required role(s) can access protected pages.
 * Users without permission are denied access.
 */
class RoleMiddleware
{

     //Verify that the user has permission to access the route.

    public function handle(Request $request, Closure $next, string $role): Response
    {
         // Check if the user is logged in.
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $allowedRoles = explode('|', $role);

           // Check if the user's role is allowed.
        if (!in_array($request->user()->role, $allowedRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
