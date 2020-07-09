<?php


namespace App\Http\Middleware;


use Closure;

class UserAuth
{
    public function handle($request, Closure $next, $guard = null) {

        $user = session()->get('user');
        $user_type = session()->get('user-type');

        if (isset($user) && ($user_type == 1 || $user_type == 2)) {
            return $next($request);
        }

        return redirect('/admin/login');
    }
}
