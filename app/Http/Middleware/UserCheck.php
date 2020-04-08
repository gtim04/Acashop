<?php

namespace App\Http\Middleware;
use Spatie\Permission\Models\Role;
use Closure;

class UserCheck
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
        if(auth()->user()->hasRole('user')){
            return $next($request);
        }
        abort(401);
    }
}
