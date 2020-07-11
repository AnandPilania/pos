<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\Category;
use App\Http\Models\Client;
use App\Http\Models\Employees;
use App\Http\Models\Product;

class DashboardController
{
    public function index()
    {
        $employees = Employees::count();
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
