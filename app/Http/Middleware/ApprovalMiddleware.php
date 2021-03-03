<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;

class ApprovalMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (!auth()->user()->approved) {
                
                // Require Approval
                // auth()->logout();
                // return redirect()->route('login')->with('message', trans('global.yourAccountNeedsAdminApproval'));
                
                // Bypass Approval
                return $next($request);
            }
        }

        return $next($request);
    }
}
