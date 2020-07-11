<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\User;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;

class EmployeesController
{
    public function index()
    {
        $employees = User::where('id', '!=', 1)->get();
        return view('admin.employees.list')
            ->with('employees', $employees)
            ->withTitle('Employee List');
    }

    public function showAddPage()
    {
        return view('admin.employees.add')
            ->withTitle('Add Employee');
    }

    public function showEditPage()
    {
        $id = request('id');
        $employee = User::where('id', $id)->first();
        return view('admin.employees.edit')
            ->with('employee', $employee)
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = new User();
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
            User::where('id', $id)->update([
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
        User::where('id', $id)->delete();

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
