<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Permission;
use App\Http\Utils\Utils;

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

    public function destroy()
    {
        $id = request('id');
        $permission = Permission::find($id);
        $permission->roles()->detach();
        $permission->users()->detach();
        $permission->delete();

        return Utils::makeResponse();
    }
}
