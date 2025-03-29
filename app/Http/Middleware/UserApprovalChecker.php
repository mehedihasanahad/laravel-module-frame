<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserApprovalChecker
{
    /**
     * Routes that should skip handle.
     *
     * @var array
     */
    /**
     * Determine if the request has a URI that should pass through.
     *
     * @param Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach (config('engine.UserApprovalCheckerExceptRoutes') as $except) {
            if ($except !== '/') $except = trim($except, '/');
            if ($request->is($except)) return true;
        }
        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->inExceptArray($request) && empty(Auth::user())) abort(500, 'Please use *withoutMiddleware([UserApprovalChecker::class])* in your route group');
        if(!$this->inExceptArray($request) && !Auth::user()->is_approved) {
            Session::flash('error', 'You are not active user. After your activation by system admin, you can work according your privileges');
            return redirect()->route('profile.index');
        }
        return $next($request);
    }
}
