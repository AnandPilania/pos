<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Permission;
use App\Http\Models\BusinessType;
use App\Http\Utils\Utils;
use Yajra\DataTables\DataTables;

class BusinessTypesController
{
    public function index()
    {
        $businessTypes = BusinessType::get();
        return view('admin.business-types.list')
            ->with('business_types', $businessTypes)
            ->withTitle('Business Type List');
    }

    public function showAddPage()
    {
        return view('admin.business-types.add')
            ->withTitle('Add BusinessType');
    }

    public function showEditPage()
    {
        $id = request('id');
        $businessType = BusinessType::find($id);

        return view('admin.business-types.edit')
            ->with([
                'business_type' => $businessType,
                'id' => $id
            ]);
    }

    public function getBusinessTypeList() {
        $list = BusinessType::get();
        return datatables()->of($list)->make(true);
    }

    public function add()
    {
        $name = request('name');

        request()->validate([
            'name' => 'required',
        ]);

        $businessTypes = new BusinessType();
        $businessTypes->name = $name;
        $businessTypes->save();

        return back()
            ->with('success', "You have successfully added.");
    }

    public function edit()
    {
        $id = request('id');
        $name = request('name');

        request()->validate([
            'name' => 'required'
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
        $id = request('id');
        $businessTypes = BusinessType::find($id);

        BusinessType::where('id', $id)->delete();

        return Utils::makeResponse();
    }

}
