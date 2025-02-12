<?php

namespace Codersgarden\MultiLangMailer\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;


class CheckPermission
{
    
    public function handle(Request $request, Closure $next)
        {
            if(in_array(Auth::user()->email, config('email-templates.allowedEmail'))) {
                return $next($request);
            }
            abort(403, 'Access Denied');
        }
}


    

