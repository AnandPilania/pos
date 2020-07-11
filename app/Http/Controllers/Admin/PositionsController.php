<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Role;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;

class PositionsController
{
    public function index()
    {
        $roles = Role::get();
        return view('admin.positions.list')
            ->with('positions', $roles)
            ->withTitle('Position List');
    }

    public function showAddPage()
    {
        return view('admin.positions.add')
            ->withTitle('Add Position');
    }

    public function showEditPage()
    {
        $id = request('id');
        $employee = Role::where('id', $id)->first();
        return view('admin.employees.edit')
            ->with('employee', $employee);
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = new Role();
        $employee->first_name = $first_name;
        $employee->last_name = $last_name;
        $employee->email = $email;
        $employee->password = hash::make($password);

        $employee->save();

        return back()
            ->with('success', "You have successfully add new employee's account.");
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

    public function toggleActive()
    {
        $id = request('id');
        $enable_flag = User::where('id', $id)->first()->enable_flag;

        User::where('id', $id)->update([
            'enable_flag' => 1 - $enable_flag,
        ]);

        return Utils::makeResponse();
    }
}
