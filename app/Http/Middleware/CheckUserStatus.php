<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;


class CheckUserStatus
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
        if (Auth::check())
        {
           if(Auth::user()->status === User::STATUS_DISABLED){
               Auth::logout();
               return redirect()->route('login')->withErrors(['email' => 'User disabled']);
           }
        }

        return $next($request);
    }
}
