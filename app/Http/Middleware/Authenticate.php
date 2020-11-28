<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        //
        if(!$request->expectsJson())
        {
            if (Request::is('admin/*')) //لو الريكويست ادمن واى حاجه بعده اوثونيكيدت وهو مش اوثونتيكيتيد
                return route('admin.login');  //هيودية على الروت
            else
                return route('login');  // ده المفروض يوديه لليوزر لوجن  ف
        }
    }
}
