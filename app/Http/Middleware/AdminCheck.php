<?php

namespace App\Http\Middleware;
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
        if(auth()->user()->hasPermissionTo('view admin')){
            return $next($request);
        } 
        abort(401);
    }
}
