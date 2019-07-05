<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;


class Status
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
        $response = $next($request);
            if(Auth::check()){
                if(Auth::user()->level == 1){
                    return redirect('/admin/dashboard');
                }elseif(Auth::user()->level == 2){
                    return redirect('/pj/dashboard');
                }
            }else{
                return redirect('/login');
            }
        return $response;
    }
}
