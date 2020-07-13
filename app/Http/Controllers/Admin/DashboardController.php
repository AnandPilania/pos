<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\Category;
use App\Http\Models\Client;
use App\Http\Models\Product;
use App\Http\Models\User;
use Spatie\Activitylog\Models\Activity;

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

    public function showLogPage()
    {
        $logs = Activity::all();
        return view('admin.logs')
            ->with('logs', $logs);
    }
}
