<?php


namespace App\Http\Controllers\Admin;


class AdminController
{
    public function index()
    {
        if (auth()->user()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login.show');
    }
}
