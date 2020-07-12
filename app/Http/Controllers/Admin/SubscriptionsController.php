<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Sanction;
use App\Http\Models\Subscription;
use App\Http\Utils\Utils;

class SubscriptionsController
{
    public function index()
    {
        $subscriptions = Subscription::get();
        return view('admin.subscriptions.list')
            ->with('subscriptions', $subscriptions)
            ->withTitle('Position List');
    }

    public function showAddPage()
    {
        $sanctions = Sanction::get();
        return view('admin.subscriptions.add')
            ->with('sanctions', $sanctions)
            ->withTitle('Add Position');
    }

    public function showEditPage()
    {
        $id = request('id');
        $subscription = Subscription::where('id', $id)->with('sanctions:id')->first();
        $sanctions = Sanction::get();

        return view('admin.subscriptions.edit')
            ->with([
                'position' => $subscription,
                'sanctions' => $sanctions,
                'id' => $id
            ]);
    }

    public function add()
    {
        $name = request('name');
        $slug = request('slug');
        $description = request('description');

        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:subscriptions'
        ]);

        if (empty(request('sanctions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one sanction.'
            ]);
        }

        $subscription = new Subscription();
        $subscription->name = $name;
        $subscription->slug = $slug;
        $subscription->description = $description;
        $subscription->save();

        $subscription->sanctions()->attach(request('sanctions'));

        return back()
            ->with('success', "Successfully added.");
    }

    public function edit()
    {
        $id = request('id');

        $name = request('name');
        $slug = request('slug');
        $description = request('description');

        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:subscriptions,slug,' . $id
        ]);

        if (empty(request('sanctions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one sanction.'
            ]);
        }

        Subscription::where('id', $id)
            ->update([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
            ]);

        $subscription = Subscription::find($id);
        $subscription->sanctions()->sync(request('sanctions'));

        return back()
            ->with('success', 'Successfully updated.');
    }

    public function delete()
    {
        $id = request('id');
        $subscription = Subscription::find($id);
        $subscription->sanctions()->detach();
        $subscription->users()->detach();
        $subscription->delete();

        return Utils::makeResponse();
    }
}
