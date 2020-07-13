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

        Utils::logActivity(auth()->user(), auth()->user(), 'logged in', ['email' => auth()->user()->email]);
        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        Utils::logActivity(auth()->user(), auth()->user(), 'logged out', ['email' => auth()->user()->email]);
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

        $id = auth()->user()->id;
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');

        request()->validate([
           // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::find($id);
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;


        if ($password != '') {
            $user->password = bcrypt($password);
        }

        if (isset(request()->image)) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();

            $original_image_path = public_path('media/avatars');
            if (!file_exists($original_image_path)) {
                mkdir($original_image_path);
            }

            request()->image->move($original_image_path, $imageName);
            $user->avatar = $imageName;
        }

        $user->save();

        return back()
            ->with('success', 'You have successfully updated your profile.');
    }

    public function token() {
        return csrf_token();
    }
}
