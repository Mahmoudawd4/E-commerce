<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect(RouteServiceProvider::HOME);
        // }
        // return $next($request);
            //بيتشك لو هو اثنتيكشن للفاريبل الجارد
            //نفترض ان انا عملت لوج ان وجو اللوج وجيت كتبت الرابط بتاع الوج ان هيجيب صفحة اللوج ان
            //ده بيتشك هل ان 
        if (Auth::guard($guard)->check()) {
            if ($guard == 'admin')  // لو الجارد اسمه admin
                return redirect(RouteServiceProvider::ADMIN);  // ADMIN موجود فى RouteServiceProvider
            else
                return redirect(RouteServiceProvider::HOME); // Home موجود فى RouteServiceProvider
        }

        return $next($request);
    }


}
