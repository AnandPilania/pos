<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Permission;
use App\Http\Models\Role;
use App\Http\Utils\Utils;

class PositionsController
{

    public function index()
    {
        if(!auth()->user()->can('position-list')) {
            return back();
        }

        $roles = Role::get();
        return view('admin.positions.list')
            ->with('positions', $roles)
            ->withTitle('Position List');
    }

    public function showAddPage()
    {
        if(!auth()->user()->can('position-create')) {
            return back();
        }

        $permissions = Permission::orderBy('slug')->get();
        return view('admin.positions.add')
            ->with('permissions', $permissions)
            ->withTitle('Add Position');
    }

    public function showEditPage()
    {
        if(!auth()->user()->can('position-edit')) {
            return back();
        }

        $id = request('id');
        $role = Role::where('id', $id)->with('permissions:id')->first();
        $permissions = Permission::get();

        return view('admin.positions.edit')
            ->with([
                'position' => $role,
                'permissions' => $permissions,
                'id' => $id
            ]);
    }

    public function add()
    {
        if(!auth()->user()->can('position-create')) {
            return back();
        }

        $name = request('name');
        $slug = request('slug');
        $description = request('description');

        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles'
        ]);

        if (empty(request('permissions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one permission.'
            ]);
        }

        $role = new Role();
        $role->name = $name;
        $role->slug = $slug;
        $role->description = $description;
        $role->save();

        $role->permissions()->attach(request('permissions'));

        return back()
            ->with('success', "Successfully added.");
    }

    public function edit()
    {
        if(!auth()->user()->can('position-edit')) {
            return back();
        }

        $id = request('id');

        $name = request('name');
        $slug = request('slug');
        $description = request('description');

        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $id
        ]);

        if (empty(request('permissions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one permission.'
            ]);
        }

        Role::where('id', $id)
            ->update([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
            ]);

        $role = Role::find($id);
        $role->permissions()->sync(request('permissions'));

        return back()
            ->with('success', 'Successfully updated.');
    }

    public function delete()
    {
        if(!auth()->user()->can('position-delete')) {
            return back();
        }

        $id = request('id');
        $role = Role::find($id);
        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return Utils::makeResponse();
    }

}
