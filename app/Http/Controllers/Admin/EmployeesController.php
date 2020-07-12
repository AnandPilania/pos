<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Role;
use App\Http\Models\User;
use App\Http\Utils\Utils;

class EmployeesController
{
    public function index()
    {
        $employees = User::where('id', '!=', 1)->with('roles')->get();
        return view('admin.employees.list')
            ->with('employees', $employees)
            ->withTitle('Employee List');
    }

    public function showAddPage()
    {
        $roles = Role::get();
        return view('admin.employees.add')
            ->with('positions', $roles)
            ->withTitle('Add Employee');
    }

    public function showEditPage()
    {
        $id = request('id');
        $employee = User::where('id', $id)->with('roles')->first();
        $roles = Role::get();

        return view('admin.employees.edit')
            ->with([
                'employee' => $employee,
                'positions' => $roles,
                'id' => $id
            ])
            ->withTitle('Edit Employee');
    }

    public function add()
    {
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if (empty(request('positions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one position.'
            ]);
        }

        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = bcrypt($password);

        $user->save();
        $user->roles()->attach(request('positions'));

        return back()
            ->with('success', "Successfully added.");
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
            'email' => 'required|email|unique:users,id,' . $id,
        ]);

        if (empty(request('positions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one position.'
            ]);
        }

        if ($password != '') {
            User::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);
        } else {
            User::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ]);
        }

        $user = User::find($id);
        $user->roles()->sync(request('positions'));

        return back()
            ->with('success', 'Successfully updated.');
    }

    public function delete()
    {
        $id = request('id');
        $user = User::find($id);
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        return Utils::makeResponse();
    }

    public function toggleActive()
    {
        $id = request('id');
        $active = User::where('id', $id)->first()->active;

        User::where('id', $id)
            ->update([
                'active' => 1 - $active,
            ]);

        return Utils::makeResponse();
    }
}
