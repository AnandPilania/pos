<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\Categories;
use App\Http\Models\Clients;
use App\Http\Models\Employees;
use App\Http\Models\Products;

class DashboardController
{
    public function index()
    {
        $employees = Employees::count();
        $customers = Clients::count();
        $products = Products::count();
        $categories = Categories::count();

        return view('admin.dashboard')->with([
            'employees' => $employees,
            'customers' => $customers,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
