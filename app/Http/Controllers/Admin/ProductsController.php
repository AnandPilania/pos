<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Category;
use App\Http\Models\Client;
use App\Http\Models\Currency;
use App\Http\Models\Product;
use App\Http\Utils\Utils;
use Spatie\ImageOptimizer\Image;

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
            ->withTitle('Product List');
    }

    public function showAddPage()
    {
        $client_id = request('client_id');
        $categories = Category::where('customer_id', $client_id)->get();
        $currency_list = Currency::get();
        return view('admin.products.add')
            ->with([
                'categories' => $categories,
                'client_id' => $client_id,
                'currency_list' => $currency_list
            ]);
    }

    public function showEditPage()
    {
        $id = request('id');
        $client_id = request('client_id');
        $product = Product::where('id', $id)->first();
        $categories = Category::where('customer_id', $product->customer_id)->get();
        $currency_list = Currency::get();
        if ($product != null) {
            return view('admin.products.edit')
                ->with([
                    'product' => $product,
                    'categories' => $categories,
                    'currency_list' => $currency_list,
                    'client_id' => $client_id
                ]);
        }
        return redirect()->route('admin.clients.products.show');
    }

    public function showDetailPage()
    {
        $id = request('id');
        $product = Product::where('id', $id)->first();
        $categories = Category::get();
        if ($product != null) {
            return view('product_detail')->with([
                'product' => $product,
                'categories' => $categories
            ]);
        }
        return redirect('/admin/products');
    }

    public function add()
    {
        $client_id = request('client_id');
        $name = request('product-name');
        $name_ar = request('product-name-ar');
        $category_id = request('category');
        $price = request('product-price');
        $video_url = request('video-url');
        $description = request('product-description');
        $description_ar = request('product-description-ar');
        $currency = request('currency');
        $direction = request('rtl-direction');

        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'product-name' => 'required',
            'product-price' => 'required',
            'category' => 'required',
            'currency' => 'required',
        ]);

        $rtl_direction = 0;
        if (isset($direction) && $direction == 'on')
            $rtl_direction = 1;

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();

        $original_image_path = public_path('media/images/products/original');
        if (!file_exists($original_image_path)) {
            mkdir($original_image_path);
        }

        $appview_image_path = public_path('media/images/products/appview');
        if (!file_exists($appview_image_path)) {
            mkdir($appview_image_path);
        }

        $thumbnail_image_path = public_path('media/images/products/thumbnail');
        if (!file_exists($thumbnail_image_path)) {
            mkdir($thumbnail_image_path);
        }

        //Save original image
        request()->image->move($original_image_path, $imageName);

        // generate appview image
        Image::make($original_image_path . DIRECTORY_SEPARATOR . $imageName)
            ->resize(1200, 1200, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($appview_image_path . DIRECTORY_SEPARATOR . $imageName);
//        ImageOptimizer::optimize($appview_image_path . DIRECTORY_SEPARATOR . $imageName);

        // generate thumbnail image
        Image::make($original_image_path . DIRECTORY_SEPARATOR . $imageName)
            ->fit(320, 320)
            ->save($thumbnail_image_path . DIRECTORY_SEPARATOR . $imageName);
//        ImageOptimizer::optimize($thumbnail_image_path . DIRECTORY_SEPARATOR . $imageName);

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match))
            $video_id = $match[1];
        else $video_id = $video_url;

        $product = new Product();
        $product->customer_id = $client_id;
        $product->name = $name;
        $product->name_second = $name_ar;
        $product->price = $price;
        $product->currency_id = $currency;
        $product->category_id = $category_id;
        $product->description = $description;
        $product->description_second = $description_ar;
        $product->picture = $imageName;
        $product->video_id = $video_id;
        $product->video_url = $video_url;
        $product->rtl_direction = $rtl_direction;

        $product->save();

        return back()
            ->with('success', 'You have successfully add new product.');
    }

    public function edit()
    {
        $id = request('id');
        $client_id = request('client_id');
        $name = request('product-name');
        $name_ar = request('product-name-ar');
        $category_id = request('category');
        $price = request('product-price');
        $description = request('product-description');
        $description_ar = request('product-description-ar');
        $currency = request('currency');
        $video_url = request('video-url');
        $direction = request('rtl-direction');

        request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'product-name' => 'required',
            'product-price' => 'required',
            'category' => 'required',
            'currency' => 'required',
        ]);

        $rtl_direction = 0;
        if (isset($direction) && $direction == 'on')
            $rtl_direction = 1;

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match))
            $video_id = $match[1];
        else $video_id = $video_url;

        if (isset(request()->image)) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();

            $original_image_path = public_path('media/images/products/original');
            if (!file_exists($original_image_path)) {
                mkdir($original_image_path);
            }

            $appview_image_path = public_path('media/images/products/appview');
            if (!file_exists($appview_image_path)) {
                mkdir($appview_image_path);
            }

            $thumbnail_image_path = public_path('media/images/products/thumbnail');
            if (!file_exists($thumbnail_image_path)) {
                mkdir($thumbnail_image_path);
            }

            //Save original image
            request()->image->move($original_image_path, $imageName);

            // generate appview image
            Image::make($original_image_path . DIRECTORY_SEPARATOR . $imageName)
                ->resize(1200, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($appview_image_path . DIRECTORY_SEPARATOR . $imageName);


            // generate thumbnail image
            Image::make($original_image_path . DIRECTORY_SEPARATOR . $imageName)
                ->fit(320, 320)
                ->save($thumbnail_image_path . DIRECTORY_SEPARATOR . $imageName);

            Product::where('id', $id)->update([
                'name' => $name,
                'name_second' => $name_ar,
                'price' => $price,
                'currency_id' => $currency,
                'category_id' => $category_id,
                'description' => $description,
                'description_second' => $description_ar,
                'video_id' => $video_id,
                'video_url' => $video_url,
                'rtl_direction' => $rtl_direction,
                'picture' => $imageName,
            ]);
        } else {
            Product::where('id', $id)->update([
                'name' => $name,
                'name_second' => $name_ar,
                'price' => $price,
                'currency_id' => $currency,
                'category_id' => $category_id,
                'description' => $description,
                'description_second' => $description_ar,
                'video_id' => $video_id,
                'rtl_direction' => $rtl_direction,
                'video_url' => $video_url
            ]);
        }
        return back()
            ->with('success', 'You have successfully updated the product.');
    }

    public function delete()
    {
        $id = request('id');
        Product::find($id)->delete();

        return Utils::makeResponse();
    }

    public function toggleActive()
    {
        $id = request('id');
        $active = Product::find($id)->active;

        Product::where('id', $id)
            ->update([
                'active' => 1 - $active,
            ]);

        return Utils::makeResponse();
    }

    public function toggleProductAllVisible()
    {
        $client_id = request('client_id');
        Product::where('customer_id', $client_id)->update(['active' => 1]);
        return back();
    }

    public function toggleProductAllInvisible()
    {
        $client_id = request('client_id');
        Product::where('customer_id', $client_id)->update(['active' => 0]);
        return back();
    }
}
