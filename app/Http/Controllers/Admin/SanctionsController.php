<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Sanction;
use App\Http\Utils\Utils;

class SanctionsController
{
    public function get()
    {
        $sanctions = Sanction::get();
        return Utils::makeResponse($sanctions);
    }

    public function add()
    {
        $name = request('name');
        $slug = request('slug');
        $description = request('description');

        $sanction = new Sanction();
        $sanction->name = $name;
        $sanction->slug = $slug;
        $sanction->description = $description;
        $sanction->save();

        if ($sanction->save()) {
            return Utils::makeResponse($sanction->id);
        }
        return Utils::makeResponse([], config('constants.response-message.fail'));
    }

    public function destroy()
    {
        $id = request('id');
        $sanction = Sanction::find($id);
        $sanction->subscriptions()->detach();
        $sanction->clients()->detach();
        $sanction->delete();

        return Utils::makeResponse();
    }
}
