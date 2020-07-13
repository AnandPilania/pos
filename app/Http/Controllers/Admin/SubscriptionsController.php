<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Sanction;
use App\Http\Models\Subscription;
use App\Http\Utils\Utils;

class SubscriptionsController
{
    public function index()
    {
        if(!auth()->user()->can('subscription-list')) {
            return back();
        }

        $subscriptions = Subscription::get();
        return view('admin.subscriptions.list')
            ->with('subscriptions', $subscriptions)
            ->withTitle('Position List');
    }

    public function showAddPage()
    {
        if(!auth()->user()->can('subscription-create')) {
            return back();
        }

        $sanctions = Sanction::get();
        return view('admin.subscriptions.add')
            ->with('sanctions', $sanctions)
            ->withTitle('Add Position');
    }

    public function showEditPage()
    {
        if(!auth()->user()->can('subscription-edit')) {
            return back();
        }

        $id = request('id');
        $subscription = Subscription::where('id', $id)->with('sanctions:id')->first();
        $sanctions = Sanction::get();

        return view('admin.subscriptions.edit')
            ->with([
                'subscription' => $subscription,
                'sanctions' => $sanctions,
                'id' => $id
            ]);
    }

    public function add()
    {
        if(!auth()->user()->can('subscription-create')) {
            return back();
        }

        $name = request('name');
        $slug = request('slug');
        $price = request('price');
        $description = request('description');

        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:subscriptions',
            'price' => 'required|numeric'
        ]);

        if (empty(request('sanctions'))) {
            return back()->withErrors([
                'msg' => 'You must select at least one sanction.'
            ]);
        }

        $subscription = new Subscription();
        $subscription->name = $name;
        $subscription->slug = $slug;
        $subscription->price = $price;
        $subscription->description = $description;
        $subscription->save();

        $subscription->sanctions()->attach(request('sanctions'));

        return back()
            ->with('success', "Successfully added.");
    }

    public function edit()
    {
        if(!auth()->user()->can('subscription-edit')) {
            return back();
        }

        $id = request('id');
        $name = request('name');
        $price = request('price');
        $slug = request('slug');
        $description = request('description');

        request()->validate([
            'name' => 'required',
            'price' => 'required|numeric',
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
                'price' => $price,
                'description' => $description,
            ]);

        $subscription = Subscription::find($id);
        $subscription->sanctions()->sync(request('sanctions'));

        return back()
            ->with('success', 'Successfully updated.');
    }

    public function delete()
    {
        if(!auth()->user()->can('subscription-delete')) {
            return back();
        }

        $id = request('id');
        $subscription = Subscription::find($id);
        $subscription->sanctions()->detach();
        $subscription->clients()->detach();
        $subscription->delete();

        return Utils::makeResponse();
    }
}
