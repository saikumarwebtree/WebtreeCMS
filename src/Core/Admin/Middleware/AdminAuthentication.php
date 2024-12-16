<?php

namespace Webtree\WebtreeCms\Core\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->can('access-cms-admin')) {
            return redirect()->route('cms.admin.login');
        }

        return $next($request);
    }
}