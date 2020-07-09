<?php


namespace App\Http\Controllers;

use App\Http\Models\Categories;
use App\Http\Models\Currency;
use App\Http\Models\Customers;
use App\Http\Models\Products;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Validator;


class APIController
{
    public function doLogin()
    {
        $validation = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $credentials = request(['email', 'password']);

        $user = Customers::select('id', 'password', 'first_name', 'last_name', 'email', 'template_no', 'category_background_color',
            'banner_color', 'font_color', 'product_background_color', 'company_logo')
            ->where([
                ['email', $credentials['email']],
                ['enable_flag', 1],
            ])->first();

        if ($user == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }

        if (!$token = auth()->attempt($credentials)) {
            return Utils::makeResponse([], config('constants.response-message.error-generate-api-token'));
        }

        $user = $user->setHidden([
            'password'
        ]);

        $categoryList = Categories::select('id', 'name', 'name_second', 'rtl_direction')->where([
            ['customer_id', $user->id],
            ['show_flag', 1],
        ])->orderBy('show_order')->with(['products' => function ($query) {
            $query->
            select('category_id', 'name', 'name_second', 'picture', 'video_id', 'price', 'description', 'description_second', 'video_url', 'currency_id')->
            where('show_flag', 1);;
        }])->get();

        return Utils::makeResponse([
            'api_token' => $token,
            'user' => $user,
            'category_array' => $categoryList
        ]);
    }

    public function getCategoriesByClient()
    {
        $email = request('email');
        $client = Customers::where('email', $email)->first();

        if (!isset($client)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $categoryList = Categories::select('id', 'name', 'name_second', 'rtl_direction')->where([
            ['customer_id', $client->id],
            ['show_flag', 1],
        ])->orderBy('show_order')->with('products:category_id,name,name_second,picture,video_id,price,description,description_second,video_url,currency_id')->get();

        return Utils::makeResponse([
            'category_array' => $categoryList
        ]);

    }

    public function getProductsByCategory()
    {
        $category_id = request('category_id');
        $search_keyword = request('search_keyword');
        $shown_count = request('shown_count');

        $where_clause = [];
        if ($search_keyword != "" && isset($search_keyword)) {
            $where_clause[] = ['name', 'like', "%$search_keyword%"];
            $where_clause[] = ['name_second', 'like', "%$search_keyword%"];
            $where_clause[] = ['description', 'like', "%$search_keyword%"];
            $where_clause[] = ['description_second', 'like', "%$search_keyword%"];
        }

        if (!isset($category_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $total_count = Products::where([
            ['category_id', $category_id],
            ['show_flag', 1]
        ])->where(function ($query) use ($where_clause) {
            if (count($where_clause) > 0) {
                $query->where([$where_clause[0]]);
                for ($i = 1; $i < count($where_clause); $i++) {
                    $query->orwhere([$where_clause[$i]]);
                }
            }
        })->count();

        $products = Products::where([
            ['category_id', $category_id],
            ['show_flag', 1]
        ])->where(function ($query) use ($where_clause) {
            if (count($where_clause) > 0) {
                $query->where([$where_clause[0]]);
                for ($i = 1; $i < count($where_clause); $i++) {
                    $query->orwhere([$where_clause[$i]]);
                }
            }
        })->offset($shown_count)->limit(12)->with('category', 'currency')->get();

        return Utils::makeResponse([
            'product_array' => $products,
            'total' => $total_count
        ]);

    }

    public function getProductDetail()
    {
        $product_id = request('product_id');
        if (!isset($product_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $product = Products::where([
            ['id', $product_id],
            ['show_flag', 1]
        ])->with('category', 'currency')->first();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        return Utils::makeResponse([
            'product' => $product
        ]);

    }

    public function getClientDetail()
    {
        $client_email = request('email');
        if (!isset($client_email)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $client = Customers::select('id', 'password', 'first_name', 'last_name', 'email', 'template_no', 'category_background_color',
            'banner_color', 'font_color', 'product_background_color', 'company_logo')
            ->where([
                ['email', $client_email],
                ['enable_flag', 1],
            ])->first();

        if ($client == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $categoryList = Categories::select('id', 'name', 'name_second', 'rtl_direction')->where([
            ['customer_id', $client->id],
            ['show_flag', 1],
        ])->orderBy('show_order')->with(['products' => function ($query) {
            $query->
            select('category_id', 'name', 'name_second', 'picture', 'video_id', 'price', 'description', 'description_second', 'video_url', 'currency_id')->
            where('show_flag', 1);;
        }])->get();

        $client = $client->setHidden([
            'password'
        ]);

        return Utils::makeResponse([
            'client' => $client,
            'category_array' => $categoryList
        ]);

    }

    public function userLogin()
    {
        $validation = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $credentials = request(['email', 'password']);

        $user = Customers::where([
            ['email', $credentials['email']],
            ['enable_flag', 1],
        ])->first();

        if ($user == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }

        if (!$token = auth()->attempt($credentials)) {
            return Utils::makeResponse([], config('constants.response-message.error-generate-api-token'));
        }

        $user_info = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'birthday' => $user->birthday,
            'startDate' => $user->start_date,
            'expireDate' => $user->expire_date,
            'price' => $user->price,
            'company' => $user->company,
            'street' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'zipCode' => $user->zipcode,
            'avatar' => $user->avatar,
            'phone' => $user->phonenumber,
            'companyLogo' => $user->company_logo
        ];

        $menu_app_settings = [
            'templateNo' => $user->template_no,
            'bannerColor' => $user->banner_color,
            'categoryBgColor' => $user->category_background_color,
            'productBgColor' => $user->product_background_color,
            'fontColor' => $user->font_color,
            'companyLogo' => $user->company_logo
        ];

        return Utils::makeResponse([
            'apiToken' => $token,
            'userInfo' => $user_info,
            'menuAppConfig' => $menu_app_settings
        ]);
    }

    public function getUserAuth()
    {
        $user = request('user');

        $client = Customers::select('id', 'first_name', 'last_name', 'email', 'template_no', 'category_background_color',
            'banner_color', 'font_color', 'product_background_color', 'company_logo')
            ->where('id', $user->id)->first();

        if ($client == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        return Utils::makeResponse([
            'user' => $client,
        ]);

    }

    public function updateMenuAppLogo()
    {
        $user = request('user');
        if (isset(request()->file)) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();

            $original_image_path = public_path('media/company_logos');
            if (!file_exists($original_image_path)) {
                mkdir($original_image_path);
            }

            request()->file->move($original_image_path, $imageName);

            Customers::where('id', $user->id)->update(['company_logo' => $imageName]);

            $client = Customers::select('template_no as templateNo', 'category_background_color as categoryBgColor',
                'banner_color as bannerColor', 'font_color as fontColor', 'product_background_color as productBgColor',
                'company_logo as companyLogo')
                ->where('id', $user->id)->first();

            if ($client == null) {
                return Utils::makeResponse([], config('constants.response-message.invalid-params'));
            }

            return Utils::makeResponse(
                $client
            );
        }

    }

    public function updateMenuAppColors()
    {
        $user = request('user');
        $template_no = request('template-no');
        $category_background_color = request('category-background-color');
        $product_background_color = request('product-background-color');
        $banner_color = request('banner-color');
        $font_color = request('font-color');

        $update_array = array();

        if (isset($category_background_color)) {
            $update_array['category_background_color'] = $category_background_color;
        }

        if (isset($product_background_color)) {
            $update_array['product_background_color'] = $product_background_color;
        }

        if (isset($banner_color)) {
            $update_array['banner_color'] = $banner_color;
        }

        if (isset($font_color)) {
            $update_array['font_color'] = $font_color;
        }

        if (isset($template_no)) {
            $update_array['template_no'] = $template_no;
        }

        Customers::where('id', $user->id)->update($update_array);

        $client = Customers::select('template_no as templateNo', 'category_background_color as categoryBgColor',
            'banner_color as bannerColor', 'font_color as fontColor', 'product_background_color as productBgColor',
            'company_logo as companyLogo')
            ->where('id', $user->id)->first();

        if ($client == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        return Utils::makeResponse(
            $client
        );

    }

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

        $total_count = Products::where('customer_id', $user->id)
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
            $order_by = "show_flag";
            $sort = "desc";
        } else if ($order_by != "name") {
            $order_by = "id";
        }

        $products = Products::where('customer_id', $user->id)
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

        $product = Products::where([
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

        $categories = Categories::where([
            ['customer_id', $user->id],
            ['show_flag', 1]
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

        $product = new Products();
        $product->customer_id = $user->id;
        $product->name = $name;
        $product->price = $price;
        $product->currency_id = $currency;
        $product->category_id = $category_id;
        $product->description = $description;
        $product->show_flag = $status;

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

        $product = Products::where([
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

            Products::where('id', $id)->update([
                'name' => $name,
                'price' => $price,
                'currency_id' => $currency,
                'category_id' => $category_id,
                'description' => $description,
                'video_id' => $video_id,
                'video_url' => $video_url,
                'picture' => $imageName,
                'show_flag' => $status
            ]);
            return Utils::makeResponse([$imageName]);
        } else {
            Products::where('id', $id)->update([
                'name' => $name,
                'price' => $price,
                'currency_id' => $currency,
                'category_id' => $category_id,
                'description' => $description,
                'video_id' => $video_id,
                'video_url' => $video_url,
                'show_flag' => $status
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

        $product = Products::where([
            ['id', $product_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $show_flag = Products::where('id', $product_id)->first()->show_flag;

        Products::where('id', $product_id)->update([
            'show_flag' => 1 - $show_flag,
        ]);

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

        $product = Products::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->get();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Products::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->update([
                'show_flag' => $state
            ]);

        return Utils::makeResponse();
    }

    public function toggleProductAllVisible()
    {
        $user = request('user');
        Products::where('customer_id', $user->id)->update(['show_flag' => 1]);
        return Utils::makeResponse();
    }

    public function toggleProductAllInvisible()
    {
        $user = request('user');
        Products::where('customer_id', $user->id)->update(['show_flag' => 0]);
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

        $product = Products::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->get();

        if ($product == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Products::where('customer_id', $user->id)
            ->whereIn('id', $product_ids)
            ->delete();

        return Utils::makeResponse();
    }

    public function getCategoriesWithPageInfo()
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
        }

        $total_count = Categories::where('customer_id', $user->id)
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
        } else if ($order_by == "status") {
            $order_by = "show_flag";
            $sort = "desc";
        } else if ($order_by != "name") {
            $order_by = "id";
        }

        $categories = Categories::where('customer_id', $user->id)
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
            ->get();

        return Utils::makeResponse([
            'currentPage' => $current_page,
            'pageSize' => $page_size,
            'totalItem' => $total_count,
            'totalPage' => $total_page,
            'data' => $categories,
            'status' => true
        ]);
    }

    public function getAllCategoryList()
    {
        $user = request('user');

        $categories = Categories::where([
            ['customer_id', $user->id],
            ['show_flag', 1],
        ])
            ->select('id as value', 'name as label', 'id as key')
            ->get();

        return Utils::makeResponse([
            'category_list' => $categories,
        ]);
    }

    public function getCategoryInfo()
    {
        $category_id = request('categoryId');
        $user = request('user');

        if (!isset($category_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Categories::where([
            ['id', $category_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = $category->setHidden([
            'customer_id'
        ]);

        return Utils::makeResponse([
            'category' => $category
        ]);
    }

    public function addCategory()
    {
        $user = request('user');
        $name = request('category-name');
        $status = request('status');

        request()->validate([
            'category-name' => 'required',
        ]);

        $category = new Categories();
        $category->customer_id = $user->id;
        $category->name = $name;
        $category->show_flag = $status;

        $category->save();

        return Utils::makeResponse();
    }

    public function updateCategory()
    {
        $id = request('id');
        $name = request('name');
        $status = request('state');
        $user = request('user');

        $validation = Validator::make(request()->all(), [
            'name' => 'required',
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Categories::where([
            ['id', $id],
            ['customer_id', $user->id]
        ])
            ->first();
        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Categories::where('id', $id)->update([
            'name' => $name,
            'show_flag' => $status
        ]);
        return Utils::makeResponse();
    }

    public function toggleActiveCategory()
    {
        $category_id = request('categoryId');
        $user = request('user');

        if (!isset($category_id)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Categories::where([
            ['id', $category_id],
            ['customer_id', $user->id]
        ])
            ->first();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $show_flag = Categories::where('id', $category_id)->first()->show_flag;

        Products::where('id', $category_id)->update([
            'show_flag' => 1 - $show_flag,
        ]);

        return Utils::makeResponse();
    }

    public function changeCategoriesState()
    {
        $user = request('user');
        $category_ids = request('categoryIds');
        $state = request('state');

        $category_ids = json_decode($category_ids);

        if (!isset($category_ids) || !isset($state) || count($category_ids) < 1 || ($state != 0 && $state != 1)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Categories::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->get();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Categories::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->update([
                'show_flag' => $state
            ]);

        return Utils::makeResponse();
    }

    public function toggleCategoryAllVisible()
    {
        $user = request('user');
        Categories::where('customer_id', $user->id)->update(['show_flag' => 1]);
        return Utils::makeResponse();
    }

    public function toggleCategoryAllInvisible()
    {
        $user = request('user');
        Categories::where('customer_id', $user->id)->update(['show_flag' => 0]);
        return Utils::makeResponse();
    }

    public function deleteCategories()
    {
        $user = request('user');
        $category_ids = request('categoryIds');

        $category_ids = json_decode($category_ids);

        if (!isset($category_ids) || count($category_ids) < 1) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $category = Categories::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->get();

        if ($category == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        Categories::where('customer_id', $user->id)
            ->whereIn('id', $category_ids)
            ->delete();

        return Utils::makeResponse();
    }
}
