<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Client;
use App\Http\Models\BusinessType;
use App\Http\Utils\Utils;

class BusinessTypesController
{
    public function index()
    {
        if(!auth()->user()->can('business-type-list')) {
            return back();
        }

        $businessTypes = BusinessType::get();
        return view('admin.business-types.list')
            ->with('business_types', $businessTypes)
            ->withTitle('Business Type List');
    }

    public function showAddPage()
    {
        if(!auth()->user()->can('business-type-create')) {
            return back();
        }

        return view('admin.business-types.add')
            ->withTitle('Add BusinessType');
    }

    public function showEditPage()
    {
        if(!auth()->user()->can('business-type-edit')) {
            return back();
        }

        $id = request('id');
        $businessType = BusinessType::find($id);

        return view('admin.business-types.edit')
            ->with([
                'business_type' => $businessType,
                'id' => $id
            ]);
    }

    public function add()
    {
        if(!auth()->user()->can('business-type-create')) {
            return back();
        }

        $name = request('name');

        request()->validate([
            'name' => 'required|unique:business_types',
        ]);

        $businessTypes = new BusinessType();
        $businessTypes->name = $name;
        $businessTypes->save();

        return back()
            ->with('success', "You have successfully added.");
    }

    public function edit()
    {
        if(!auth()->user()->can('business-type-edit')) {
            return back();
        }

        $id = request('id');
        $name = request('name');

        request()->validate([
            'name' => 'required|unique:business_types,name,' . $id
        ]);

        BusinessType::where('id', $id)
            ->update([
                'name' => $name
            ]);

        return back()
            ->with('success', 'You have successfully updated.');
    }

    public function delete()
    {
        if(!auth()->user()->can('business-type-delete')) {
            return back();
        }

        $id = request('id');
        Client::where('business_type_id', $id)
            ->update([
                'business_type_id' => NULL
            ]);

        BusinessType::where('id', $id)->delete();

        return Utils::makeResponse();
    }

}
