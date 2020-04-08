<?php

namespace App\Http\Middleware;
use Spatie\Permission\Models\Role;
use Closure;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->hasRole(Role::whereNotIn('name', ['user'])->pluck('name')->all()) ){
            return $next($request);
        } 
        abort(401);
    }
}
