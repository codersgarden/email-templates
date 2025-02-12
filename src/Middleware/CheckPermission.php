<?php

namespace Codersgarden\MultiLangMailer\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;


class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user
        $user = Auth::user();

        $allowedEmail  = config('email-templates.allowedEmail');

        if ($allowedEmail && (!$user || $user->email !== $allowedEmail)) {
            abort(403, 'Access Denied');
        }

        $allowedRoutes = [
            'admin.templates.index',
            'admin.templates.create',
            'admin.templates.edit',
            'admin.placeholders.index',
            'admin.placeholders.create',
            'admin.placeholders.edit',
        ];

        // Allow access if the user is on an allowed route
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // If the route is not allowed, deny access
        abort(403, 'Access Denied');


    }


    
}
