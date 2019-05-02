<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

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

        $query = Pengguna::where(['email' => $request->email, 'password' => $request->password])->first();
        if($query->level == 1){
            return redirect('admin/dashboard');
        }else if($query->level == 2){
            return redirect('pj/dashboard');
        }
        return $next($request);
    }
}
