<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Category;
use App\Http\Models\Client;
use App\Http\Models\Product;

class CategoriesController
{
    public function index()
    {
        $client_id = request('client_id');

        if (!isset($client_id)) {
            return redirect()->back();
        }

        $categories = Category::where('customer_id', $client_id)->get();

        return view('admin.categories.list')
            ->with([
                'categories' => $categories,
                'client_id' => $client_id
            ])
            ->withTitle('Categories');

    }
}
