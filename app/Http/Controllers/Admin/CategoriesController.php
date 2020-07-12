<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Category;
use App\Http\Models\Client;
use App\Http\Models\Product;
use App\Http\Utils\Utils;

class CategoriesController
{
    public function index()
    {
        $client_id = request('client_id');
        $categories = Category::where('customer_id', $client_id)->get();

        return view('admin.categories.list')
            ->with([
                'categories' => $categories,
                'client_id' => $client_id
            ])
            ->withTitle('Category List');
    }

    public function showAddPage()
    {
        $client_id = request('client_id');
        return view('admin.categories.add')
            ->with('client_id', $client_id);
    }

    public function showEditPage()
    {
        $id = request('id');
        $client_id = request('client_id');
        $category = Category::where('id', $id)->first();
        if ($category != null) {
            return view('admin.categories.edit')
                ->with([
                    'category' => $category,
                    'client_id' => $client_id
                ]);
        }
        return redirect()->route('admin.clients.categories.show', $client_id);
    }

    public function showDetailPage()
    {
        $id = request('id');
        $client_id = request('client_id');
        $category = Category::find($id);
        $products = Product::where('category_id', $id)->get();
        if ($category != null) {
            return view('category_detail')->with([
                'category' => $category,
                'products' => $products,
                'client_id' => $client_id
            ]);
        }
        return redirect()->route('admin.clients.categories.show', $client_id);
    }

    public function add()
    {
        $client_id = request('client_id');
        $name = request('category-name');
        $name_ar = request('category-name-ar');
        $order = request('order');
        $direction = request('rtl-direction');

        request()->validate([
            'category-name' => 'required',
        ]);

        if (isset($order)) {
            if (Category::where([
                    ['customer_id', $client_id],
                    ['show_order', $order],
                ])->count() > 0) {
                return back()
                    ->with('warning', 'The Order is already taken.');
            }
        }

        $rtl_direction = 0;
        if (isset($direction) && $direction == 'on')
            $rtl_direction = 1;
        $category = new Category();
        $category->customer_id = $client_id;
        $category->name = $name;
        $category->name_second = $name_ar;
        $category->show_order = $order;
        $category->rtl_direction = $rtl_direction;

        $category->save();

        return back()
            ->with('success', 'You have successfully add new category.');
    }

    public function edit()
    {
        $id = request('id');
        $name = request('category-name');
        $name_ar = request('category-name-ar');
        $order = request('order');
        $direction = request('rtl-direction');

        request()->validate([
            'category-name' => 'required',
        ]);

        $customer_id = Category::find($id)->customer_id;
        if (isset($order)) {
            if (Category::where([
                    ['customer_id', $customer_id],
                    ['show_order', $order],
                    ['id', '!=', $id],
                ])->count() > 0) {
                return back()
                    ->with('warning', 'The Order is already taken.');
            }
        }

        $rtl_direction = 0;
        if (isset($direction) && $direction == 'on')
            $rtl_direction = 1;

        Category::where('id', $id)->update([
            'name' => $name,
            'name_second' => $name_ar,
            'show_order' => $order,
            'rtl_direction' => $rtl_direction
        ]);

        return back()
            ->with('success', 'You have successfully updated category.');
    }

    public function delete()
    {
        $id = request('id');
        Product::where('category_id', $id)->delete();
        Category::where('id', $id)->delete();
        return Utils::makeResponse();
    }

    public function toggleActive()
    {
        $id = request('id');
        $active = Category::find($id)->active;

        Category::where('id', $id)->update([
            'active' => 1 - $active,
        ]);

        return Utils::makeResponse();
    }

    public function toggleCategoryAllVisible()
    {
        $client_id = request('client_id');
        Category::where('customer_id', $client_id)->update(['active' => 1]);
        return back();
    }

    public function toggleCategoryAllInvisible()
    {
        $client_id = request('client_id');
        Category::where('customer_id', $client_id)->update(['active' => 0]);
        return back();
    }
}
