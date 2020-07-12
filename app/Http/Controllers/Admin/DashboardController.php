<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\Category;
use App\Http\Models\Client;
use App\Http\Models\Product;
use App\Http\Models\User;

class DashboardController
{
    public function index()
    {
        $employees = User::count();
        $customers = Client::count();
        $products = Product::count();
        $categories = Category::count();

        return view('admin.dashboard')->with([
            'employees' => $employees,
            'customers' => $customers,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
