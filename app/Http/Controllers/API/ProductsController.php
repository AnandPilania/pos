<?php


namespace App\Http\Controllers\API;


use App\Http\Models\Category;
use App\Http\Models\Currency;
use App\Http\Models\Product;
use App\Http\Utils\Utils;

class ProductsController
{
    public function getProductsWithPageInfo()
    {
        $page_size = request('pageSize');
        $current_page = request('currentPage');
        $order_by = request('orderBy');
        $sort = request('sort');
        $search = request('search');
        $user = request('user');

        $where_clause = [];
        if (isset($search) && $search != "") {
            $where_clause[] = ['name', 'like', "%$search%"];
            $where_clause[] = ['name_second', 'like', "%$search%"];
            $where_clause[] = ['description', 'like', "%$search%"];
            $where_clause[] = ['description_second', 'like', "%$search%"];
        }

        $total_count = Product::where('customer_id', $user->id)
            ->where(function ($query) use ($where_clause) {
                if (count($where_clause) > 0) {
                    $query->where([$where_clause[0]]);
                    for ($i = 1; $i < count($where_clause); $i++) {
                        $query->orwhere([$where_clause[$i]]);
                    }
                }
            })
            ->count();

        $page_size = (int)$page_size;
        $current_page = (int)$current_page;
        if ($page_size < 1)
            $page_size = 10;

        if ($current_page < 1)
            $current_page = 1;

        $total_page = ceil($total_count / $page_size);

        if ($sort == "" || $sort != "asc" || $sort != "desc") {
            $sort = "asc";
        }

        if ($order_by == "") {
            $order_by = "id";
        } else if ($order_by == "category") {
            $order_by = "category_id";
        } else if ($order_by == "status") {
            $order_by = "active";
            $sort = "desc";
        } else if ($order_by != "name") {
            $order_by = "id";
        }

        $products = Product::where('customer_id', $user->id)
            ->where(function ($query) use ($where_clause) {
                if (count($where_clause) > 0) {
                    $query->where([$where_clause[0]]);
                    for ($i = 1; $i < count($where_clause); $i++) {
                        $query->orwhere([$where_clause[$i]]);
                    }
                }
            })
            ->offset($page_size * ($current_page - 1))
            ->limit($page_size)
            ->orderBy($order_by, $sort)
            ->with('category', 'currency')
            ->get();

        return Utils::makeResponse([
            'currentPage' => $current_page,
            'pageSize' => $page_size,
            'totalItem' => $total_count,
            'totalPage' => $total_page,
            'data' => $products,
            'status' => true
        ]);
    }

    public function getProductInfo()
    {
        $product_id = request('productId');
        $user = request('user');

        if (!isset($product_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = Product::where([
            ['id', $product_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = $product->setHidden([
            'customer_id'
        ]);

        $categories = Category::where([
            ['customer_id', $user->id],
            ['active', 1]
        ])
            ->select('id as value', 'name as label', 'id as key')
            ->get();

        $currencyList = Currency::select('id as value', 'name as label')->get();

        return Utils::makeResponse([
            'product' => $product,
            'categoryList' => $categories,
            'currencyList' => $currencyList
        ]);
    }

    public function addProduct()
    {
        $user = request('user');
        $name = request('name');
        $category_id = request('category');
        $price = request('price');
        $description = request('description');
        $currency = request('currency');
        $status = request('status');

        $validation = Validator::make(request()->all(), [
            'name' => 'required',
            //'category' => 'required',
            //'currency' => 'required'
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = new Product();
        $product->customer_id = $user->id;
        $product->name = $name;
        $product->price = $price;
        $product->currency_id = $currency;
        $product->category_id = $category_id;
        $product->description = $description;
        $product->active = $status;

        if ($product->save()) {
            return Utils::makeResponse($product->id);
        }
        return Utils::makeResponse([], config('constants.response-message.fail'));
    }

    public function updateProduct()
    {
        $id = request('id');
        $name = request('name');
        $category_id = request('category');
        $price = request('price');
        $description = request('description');
        $currency = request('currency');
        $video_url = request('video-url');
        $status = request('state');
        $user = request('user');

        $validation = Validator::make(request()->all(), [
            'name' => 'required',
            //'category' => 'required',
            //'currency' => 'required'
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = Product::where([
            ['id', $id],
            ['customer_id', $user->id]
        ])
            ->first();
        if ($product == null)
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));

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
                'price' => $price,
                'currency_id' => $currency,
                'category_id' => $category_id,
                'description' => $description,
                'video_id' => $video_id,
                'video_url' => $video_url,
                'img' => $imageName,
                'active' => $status
            ]);
            return Utils::makeResponse([$imageName]);
        } else {
            Product::where('id', $id)->update([
                'name' => $name,
                'price' => $price,
                'currency_id' => $currency,
                'category_id' => $category_id,
                'description' => $description,
                'video_id' => $video_id,
                'video_url' => $video_url,
                'active' => $status
            ]);
            return Utils::makeResponse();
        }

    }

    public function toggleActiveProduct()
    {
        $product_id = request('productId');
        $user = request('user');

        if (!isset($product_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = Product::where([
            ['id', $product_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $pr = Product::find($product_id);
        $pr->active = 1 - $pr->active;
        $pr->save();

        return Utils::makeResponse();
    }

    public function changeProductsState()
    {
        $product_ids = request('productIds');
        $state = request('state');
        $user = request('user');

        $product_ids = json_decode($product_ids);

        if (!isset($product_ids) || !isset($state) || count($product_ids) < 1 || ($state != 0 && $state != 1)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = Product::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->get();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Product::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->update([
                'active' => $state
            ]);

        return Utils::makeResponse();
    }

    public function toggleProductAllVisible()
    {
        $user = request('user');
        Product::where('customer_id', $user->id)->update(['active' => 1]);
        return Utils::makeResponse();
    }

    public function toggleProductAllInvisible()
    {
        $user = request('user');
        Product::where('customer_id', $user->id)->update(['active' => 0]);
        return Utils::makeResponse();
    }

    public function deleteProducts()
    {
        $product_ids = request('productIds');
        $user = request('user');

        $product_ids = json_decode($product_ids);

        if (!isset($product_ids) || count($product_ids) < 1) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = Product::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->get();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Product::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->delete();

        return Utils::makeResponse();
    }
}
