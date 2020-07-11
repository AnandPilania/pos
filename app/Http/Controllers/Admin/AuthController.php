<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Utils\Utils;

class AuthController
{
    public function showLoginPage()
    {
        return view('admin.auth.login');
    }

    public function login()
    {
        if (auth()->attempt(request(['email', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'The email or password is incorrect, please try again'
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.home');
    }

    public function register()
    {
        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::create(request(['email', 'password', 'first_name', 'last_name']));
        return Utils::makeResponse([
            'user' => $user
        ]);
    }

    public function token() {
        return csrf_token();
    }
}
