<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\Admin;
use App\Http\Models\Employees;
use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function showLoginPage()
    {
        return view('admin.auth.login');
    }

    public function showProfilePage() {
        $user = auth()->user();
        return view('admin.auth.profile')
            ->with('user', $user)
            ->withTitle('Profile');
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

    public function editProfile() {
        $id = request('id');
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');
        $user_type = session()->get('user-type');

        if ($user_type === 3) {
            request()->validate([
                'first-name' => 'required',
                'last-name' => 'required',
                'email' => 'required|email',
            ]);
        } else {
            request()->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                'first-name' => 'required',
                'last-name' => 'required',
                'email' => 'required|email',
            ]);
        }

        $update_array = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
        );

        if ($password != '') {
            $update_array['password'] = hash::make($password);
        }

        if ($user_type !== 3) {
            if (isset(request()->image)) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();

                $original_image_path = public_path('media/avatars');
                if (!file_exists($original_image_path)) {
                    mkdir($original_image_path);
                }

                request()->image->move($original_image_path, $imageName);
                $update_array['avatar'] = $imageName;
            }
        }

        if($user_type === 1) {
            Admin::where('id', $id)->update($update_array);
            session()->put('user', Admin::where('id', $id)->first());
        } else if($user_type === 2) {
            Employees::where('id', $id)->update($update_array);
            session()->put('user', Employees::where('id', $id)->first());
        } else {
            Customers::where('id', $id)->update($update_array);
            session()->put('user', Customers::where('id', $id)->first());
        }

        return back()
            ->with('success', 'You have successfully updated your profile.');
    }

    public function token() {
        return csrf_token();
    }
}
