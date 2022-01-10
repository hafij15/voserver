<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use HasPermissionsTrait;
use Brian2694\Toastr\Facades\Toastr;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        
        // $role = $request->route()->parameters();
        if($request->user()){
            foreach ($request->user()->roles as $role) {
                $role = $role->slug;
            }
        }
              
        // $permission='dashboard';
        // dd($request->user()->can('dashboard'));
        

        if(!$request->user() && !$request->user()->hasRole($role)) {

            abort(404);

        }

       if($permission !== null && !$request->user()->can($permission)) {

             abort(404);
       }


       return $next($request);   

    }

    
}
