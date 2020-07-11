<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Client;
use App\Http\Models\Product;

class ProductsController
{
    public function index()
    {
        $client_id = request('client_id');

        if (!isset($client_id)) {
            return redirect()->back();
        }

        $products = Product::where('customer_id', $client_id)->with('category', 'currency')->get();

        return view('admin.products.list')
            ->with([
                'products' => $products,
                'client_id' => $client_id
            ])
            ->withTitle('Products');

    }
}