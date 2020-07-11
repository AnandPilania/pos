<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Permission;
use App\Http\Models\Role;
use App\Http\Utils\Utils;
use Hamcrest\Util;
use Illuminate\Support\Facades\Hash;

class PermissionsController
{
    public function get()
    {
        $permissions = Permission::get();
        return Utils::makeResponse($permissions);
    }

    public function add()
    {
        $name = request('name');
        $slug = request('slug');
        $description = request('description');

        $permission = new Permission();
        $permission->name = $name;
        $permission->slug = $slug;
        $permission->description = $description;
        $permission->save();

        if ($permission->save()) {
            return Utils::makeResponse($permission->id);
        }
        return Utils::makeResponse([], config('constants.response-message.fail'));
    }

    public function edit()
    {
        $id = request('id');
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email',
        ]);

        if ($password != '') {
            Role::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => hash::make($password),
            ]);
        } else {
            User::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ]);
        }

        return back()
            ->with('success', 'You have successfully updated employee\'s account.');
    }

    public function destroy()
    {
        $id = request('id');
        Role::where('id', $id)->delete();

        return Utils::makeResponse();
    }
}
