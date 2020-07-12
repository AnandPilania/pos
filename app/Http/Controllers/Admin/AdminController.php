<?php


namespace App\Http\Controllers\Admin;

use App\Http\Models\Admin;
use App\Http\Models\Category;
use App\Http\Models\Currency;
use App\Http\Models\Customers;
use App\Http\Models\Invoices;
use App\Http\Models\Product;
use App\Http\Models\Employees;
use App\Http\Models\Role;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\In;
use Intervention\Image\Facades\Image;
use Barryvdh\DomPDF\Facade as PDF;
//use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class AdminController
{

    public function index()
    {
        //$user = session()->get('user');
        //$user_type = session()->get('user-type');

        if (auth()->user()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login.show');

//        if (isset($user) && isset($user_type)) {
//            if ($user_type == 3) {
//                return redirect('/admin/my-page');
//            } else {
//                return redirect('/admin/dashboard');
//            }
//        } else {
//            return view('admin.auth.login');
//        }
    }

    public function doLogin()
    {

        $email = request('login-username');
        $password = request('login-password');

        if (!isset($email)) {
            session()->flash('error-msg', 'Please enter valid email.');
            return redirect()->back();
        }
        if (!isset($password)) {
            session()->flash('error-msg', 'Please enter valid password.');
            return redirect()->back();
        }

        $admin = Admin::where('email', $email)->first();
        if (!isset($admin)) {

            $user = Employees::where('email', $email)->first();
            if (!isset($user)) {

                $customer = Customers::where('email', $email)->first();
                if (!isset($customer)) {
                    session()->flash('error-msg', 'User not found.');
                    return redirect()->back();
                }
                if (!hash::check($password, $customer->password)) {
                    session()->flash('error-msg', 'Invalid password.');
                    return redirect()->back();
                }
                session()->put('user', $customer);
                session()->put('user-type', 3);
                return redirect('/admin/my-page');
            }

            if (!hash::check($password, $user->password)) {
                session()->flash('error-msg', 'Invalid password.');
                return redirect()->back();
            }

            session()->put('user', $user);
            session()->put('user-type', 2);
            return redirect('/admin/dashboard');
        }

        if (!hash::check($password, $admin->password)) {
            session()->flash('error-msg', 'Invalid password.');
            return redirect()->back();
        }

        session()->put('user', $admin);
        session()->put('user-type', 1);
        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        session()->remove('user');
        session()->remove('user-type');
        session()->remove('selected-client-id');
        return redirect('/admin/login');
    }



    public function showMyPage() {
        $user = session()->get('user');
        $user_type = session()->get('user-type');

        if ($user_type == 3) {
            return redirect("/admin/customers/detail/$user->id");
        } else {
            return view('admin.auth.profile')->with('user', $user);
        }
    }



    public function showEmployeesPage()
    {
        $employees = Employees::get();
        return view('employees')->with('employees', $employees);
    }

    public function showEmployeeAddPage()
    {
        return view('employee_add');
    }

    public function showEmployeeEditPage()
    {
        $id = request('id');
        $employee = Employees::where('id', $id)->first();
        return view('employee_edit')->with('employee', $employee);
    }

    public function addEmployee()
    {
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = new Employees();
        $employee->first_name = $first_name;
        $employee->last_name = $last_name;
        $employee->email = $email;
        $employee->password = hash::make($password);

        $employee->save();

        return back()
            ->with('success', "You have successfully add new employee's account.");
    }

    public function editEmployee()
    {
        $id = request('id');
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email',
        ]);

        if ($password != '') {
            Employees::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => hash::make($password),
            ]);
        } else {
            Employees::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ]);
        }

        return back()
            ->with('success', 'You have successfully updated employee\'s account.');
    }

    public function delEmployee()
    {
        $id = request('id');
        Employees::where('id', $id)->delete();

        return Utils::makeResponse();
    }

    public function toggleEmployeeEnable()
    {
        $id = request('id');
        $enable_flag = Employees::where('id', $id)->first()->enable_flag;

        Employees::where('id', $id)->update([
            'enable_flag' => 1 - $enable_flag,
        ]);

        return Utils::makeResponse();
    }

    public function showCustomersPage()
    {
        $customers = Customers::get();
        return view('customers')->with('customers', $customers);
    }

    public function showCustomerAddPage()
    {
        return view('customer_add');
    }

    public function showCustomerEditPage()
    {
        $id = request('id');
        $customer = Customers::where('id', $id)->first();
        if ($customer != null) {
            return view('customer_edit')->with([
                'customer' => $customer
            ]);
        }
        return redirect('/admin/customers');
    }

    public function showCustomerDetailPage()
    {
        $id = request('id');
        $customer = Customers::where('id', $id)->first();

        // Permission check
        $user_type = session()->get('user-type');
        $user = session()->get('user');
        if($user_type === 3 && $user->id != $id) {
            return redirect('/admin/my-page');
        }

        $products = Product::where('customer_id', $id)->with('category','currency')->get();
        if ($customer != null) {
            return view('customer_detail')->with([
                'customer' => $customer,
                'products' => $products
            ]);
        }
        return redirect('/admin/customers');
    }

    public function addCustomer()
    {
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');
        $birthday = request('birthday');
        $gender = request('gender');
        $phonenumber = request('phone-number');
        $company = request('company');
        $address = request('address');
        $city = request('city');
        $state = request('state');
        $zipcode = request('zip-code');
        $start_date = request('start-date');
        $expire_date = request('expire-date');
        $price = request('price');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'birthday' => 'required|date',
            'phone-number' => 'required',
            'start-date' => 'required|date',
            'expire-date' => 'required|date',
            'price' => 'required|numeric',
        ]);

        $birthday = strtotime($birthday);
        $birthday = date('Y-m-d', $birthday);

        $start_date = strtotime($start_date);
        $start_date = date('Y-m-d', $start_date);

        $expire_date = strtotime($expire_date);
        $expire_date = date('Y-m-d', $expire_date);

        $customer = new Customers();
        $customer->first_name = $first_name;
        $customer->last_name = $last_name;
        $customer->email = $email;
        $customer->password = hash::make($password);
        $customer->birthday = $birthday;
        $customer->gender = $gender;
        $customer->phonenumber = $phonenumber;
        $customer->company = $company;
        $customer->address = $address;
        $customer->city = $city;
        $customer->state = $state;
        $customer->zipcode = $zipcode;
        $customer->start_date = $start_date;
        $customer->expire_date = $expire_date;
        $customer->price = $price;

        $customer->save();

        $invoice = new Invoices();
        $invoice->customer_id = $customer->id;
        $invoice->start_date = $start_date;
        $invoice->expire_date = $expire_date;
        $invoice->price = $price;

        $invoice->save();

        Customers::where('id', $customer->id)->update([
            'current_invoice_id' => $invoice->id
        ]);

        return back()
            ->with('success', 'You have successfully add new customer.');
    }

    public function editCustomer()
    {
        $id = request('id');
        $first_name = request('first-name');
        $last_name = request('last-name');
        $email = request('email');
        $password = request('password');
        $birthday = request('birthday');
        $gender = request('gender');
        $phonenumber = request('phone-number');
        $company = request('company');
        $address = request('address');
        $city = request('city');
        $state = request('state');
        $zipcode = request('zip-code');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email',
            'birthday' => 'required|date',
            'phone-number' => 'required',
        ]);

        $birthday = strtotime($birthday);
        $birthday = date('Y-m-d', $birthday);

        if ($password != '') {
            Customers::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => hash::make($password),
                'birthday' => $birthday,
                'gender' => $gender,
                'phonenumber' => $phonenumber,
                'company' => $company,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zipcode' => $zipcode,
            ]);
        } else {
            Customers::where('id', $id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'birthday' => $birthday,
                'gender' => $gender,
                'phonenumber' => $phonenumber,
                'company' => $company,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zipcode' => $zipcode,
            ]);
        }

        return back()
            ->with('success', 'You have successfully updated the customer\'s account');
    }

    public function resuscitateCustomer()
    {
        $id = request('id');
        $start_date = request('start-date');
        $expire_date = request('expire-date');
        $price = request('price');
        $add_flag = request('add_flag');

        request()->validate([
            'start-date' => 'required|date',
            'expire-date' => 'required|date',
            'price' => 'required|numeric',
        ]);

        $start_date = date('Y-m-d', strtotime($start_date));
        $expire_date = date('Y-m-d', strtotime($expire_date));

        if ($add_flag == 1) {
            $invoice = new Invoices();
            $invoice->customer_id = $id;
            $invoice->start_date = $start_date;
            $invoice->expire_date = $expire_date;
            $invoice->price = $price;

            $invoice->save();

            Customers::where('id', $id)->update([
                'start_date' => $start_date,
                'expire_date' => $expire_date,
                'price' => $price,
                'current_invoice_id' => $invoice->id,
            ]);
        } else {
            Invoices::where('id', Customers::where('id', $id)->first()->current_invoice_id)->update([
                'start_date' => $start_date,
                'expire_date' => $expire_date,
                'price' => $price,
            ]);

            Customers::where('id', $id)->update([
                'start_date' => $start_date,
                'expire_date' => $expire_date,
                'price' => $price,
            ]);
        }



        return Utils::makeResponse();
    }

    public function delCustomer()
    {
        $id = request('id');
        Customers::where('id', $id)->delete();

        return Utils::makeResponse();
    }

    public function toggleCustomerEnable()
    {
        $id = request('id');
        $enable_flag = Customers::where('id', $id)->first()->enable_flag;

        Customers::where('id', $id)->update([
            'enable_flag' => 1 - $enable_flag,
        ]);

        return Utils::makeResponse();
    }

    public function toggleCustomerAddProduct()
    {
        $customer_id = request('customer_id');
        $product_id = request('product_id');
        $exist_flag = CustomerProducts::where([
            'customer_id' => $customer_id,
            'product_id' => $product_id,
        ])->exists();

        if ($exist_flag) {
            CustomerProducts::where([
                'customer_id' => $customer_id,
                'product_id' => $product_id,
            ])->delete();
        } else {
            $customer_product = new CustomerProducts();
            $customer_product->customer_id = $customer_id;
            $customer_product->product_id = $product_id;
            $customer_product->save();
        }

        return Utils::makeResponse();
    }

    public function printCustomerInvoice()
    {
        $id = request('id');
        $customer = Customers::where('id', $id)->first();
        $invoices = Invoices::where('customer_id', $id)->get();
        $total = Invoices::where('customer_id', $id)->sum('price');

        $pdf = PDF::loadView('customer_invoice_pdf', [
            'customer' => $customer,
            'invoices' => $invoices,
            'total' => $total,
            ]);
        //$pdf->save(storage_path('app/public') . '_filename.pdf');
        return $pdf->download('customer_invoice.pdf');

    }

    public function showCustomerInvoicePrintPreviewPage() {

        $id = request('id');
        $customer = Customers::where('id', $id)->first();
        $invoices = Invoices::where('customer_id', $id)->get();
        $total = Invoices::where('customer_id', $id)->sum('price');

        return view('customer_invoice_print_preview')->with([
            'customer' => $customer,
            'invoices' => $invoices,
            'total' => $total,
        ]);
    }

    public function showProductFirstPage()
    {
        if (session()->get('user-type') != 3) {

            $client_id = session()->get('selected-client-id');
            if (isset($client_id)) {
                return redirect('/admin/products/' . $client_id);
            }
            $customers_cnt = Customers::count();
            if ($customers_cnt > 0) {
                $customers = Customers::get();
                return redirect('/admin/products/' . $customers[0]->id);
            }

            return view('customer_add')->with([
                'warning' => 'Warning'
            ]);
        }
        return redirect('/admin/products/'.session()->get('user')->id);
    }

    public function showProductsPage()
    {
        $customer_id = request('customer_id');
        if (session()->get('user-type') != 3 || $customer_id == session()->get('user')->id) {
            $products = Product::where('customer_id', $customer_id)->with('category', 'currency')->get();
            $customers = Customers::get();

            return view('products')->with([
                'products' => $products,
                'customer_id' => $customer_id,
                'customers' => $customers
            ]);
        }
        return redirect('/admin/products/'.session()->get('user')->id);
    }

    public function showProductAddPage()
    {
        $customer_id = request('customer_id');
        if (session()->get('user-type') == 3) {
            $customer_id = session()->get('user')->id;
        }
        $categories = Category::where('customer_id', $customer_id)->get();
        $currency_list = Currency::get();
        return view('product_add')->with([
            'categories' => $categories,
            'customer_id' => $customer_id,
            'currency_list' => $currency_list
        ]);
    }

    public function showProductEditPage()
    {
        $id = request('id');
        $product = Product::where('id', $id)->first();
        $categories = Category::where('customer_id', $product->customer_id)->get();
        $currency_list = Currency::get();
        if ($product != null) {
            return view('product_edit')->with([
                'product' => $product,
                'categories' => $categories,
                'currency_list' => $currency_list,
            ]);
        }
        return redirect('/admin/products');
    }

    public function showProductDetailPage()
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

    public function addProduct()
    {
        $customer_id = request('customer_id');
        if (session()->get('user-type') == 3) {
            $customer_id = session()->get('user')->id;
        }
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
            ->resize(1200, 1200, function($constraint) {
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
        $product->customer_id = $customer_id;
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

    public function editProduct()
    {
        $id = request('id');
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
                ->resize(1200, 1200, function($constraint) {
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

    public function delProduct()
    {
        $id = request('id');
        Product::where('id', $id)->delete();

        return Utils::makeResponse();
    }

    public function toggleProductVisible()
    {
        $id = request('id');
        $show_flag = Product::where('id', $id)->first()->show_flag;

        Product::where('id', $id)->update([
            'show_flag' => 1 - $show_flag,
        ]);

        return Utils::makeResponse();
    }

    public function toggleProductAllVisible() {

        $user_type = session()->get('user-type');
        if ($user_type === 1 || $user_type === 2) {
            Product::query()->update(['show_flag' => 1]);
        } else {
            $customer_id = session()->get('user')->id;
            Product::where('customer_id', $customer_id)->update(['show_flag' => 1]);
        }
        return back();
    }

    public function toggleProductAllInvisible() {

        $user_type = session()->get('user-type');
        if ($user_type === 1 || $user_type === 2) {
            Product::query()->update(['show_flag' => 0]);
        } else {
            $customer_id = session()->get('user')->id;
            Product::where('customer_id', $customer_id)->update(['show_flag' => 0]);
        }
        return back();

    }

    public function showCategoryFirstPage()
    {
        if (session()->get('user-type') != 3) {

            $client_id = session()->get('selected-client-id');
            if (isset($client_id)) {
                return redirect('/admin/categories/' . $client_id);
            }

            $customers_cnt = Customers::count();
            if ($customers_cnt > 0) {
                $customers = Customers::get();
                return redirect('/admin/categories/' . $customers[0]->id);
            }

            return view('customer_add')->with([
                'warning' => 'Warning'
            ]);
        }
        return redirect('/admin/categories/'.session()->get('user')->id);
    }

    public function showCategoriesPage()
    {
        $customer_id = request('customer_id');
        if (session()->get('user-type') != 3 || $customer_id == session()->get('user')->id) {
            $categories = Category::where('customer_id', $customer_id)->orderBy('show_order')->get();
            $customers = Customers::get();
            return view('categories')->with([
                'categories' => $categories,
                'customer_id' => $customer_id,
                'customers' => $customers
            ]);
        }
        return redirect('/admin/categories/'.session()->get('user')->id);
    }

    public function showCategoryAddPage()
    {
        $customer_id = request('customer_id');
        if (session()->get('user-type') == 3) {
            $customer_id = session()->get('user')->id;
        }
        return view('category_add')->with('customer_id', $customer_id);
    }

    public function showCategoryEditPage()
    {
        $id = request('id');
        $category = Category::where('id', $id)->first();
        if ($category != null) {
            return view('category_edit')->with([
                'category' => $category
            ]);
        }
        return redirect('/admin/categories');
    }

    public function showCategoryDetailPage()
    {
        $id = request('id');
        $category = Category::where('id', $id)->first();
        $products = Product::where('category_id', $id)->get();
        if ($category != null) {
            return view('category_detail')->with([
                'category' => $category,
                'products' => $products
            ]);
        }
        return redirect('/admin/categories');
    }

    public function addCategory()
    {
        $customer_id = request('customer_id');
        if (session()->get('user-type') == 3) {
            $customer_id = session()->get('user')->id;
        }
        $name = request('category-name');
        $name_ar = request('category-name-ar');
        $tags = request('category-tags');
        $tags_ar = request('category-tags-ar');
        $order = request('order');
        $direction = request('rtl-direction');

        request()->validate([
            'category-name' => 'required',
        ]);

        if(isset($order)) {
            if (Category::where([
                ['customer_id', $customer_id],
                ['show_order', $order],
            ])->count() > 0) {
                return back()
                    ->with('warning', 'The Order is already set.');
            }
        }

        $rtl_direction = 0;
        if (isset($direction) && $direction == 'on')
            $rtl_direction = 1;
        $category = new Category();
        $category->customer_id = $customer_id;
        $category->name = $name;
        $category->tags = $tags;
        $category->name_second = $name_ar;
        $category->tags_second = $tags_ar;
        $category->show_order = $order;
        $category->rtl_direction = $rtl_direction;

        $category->save();

        return back()
            ->with('success', 'You have successfully add new category.');
    }

    public function editCategory()
    {
        $id = request('id');
        $name = request('category-name');
        $name_ar = request('category-name-ar');
        $tags = request('category-tags');
        $tags_ar = request('category-tags-ar');
        $order = request('order');
        $direction = request('rtl-direction');

        request()->validate([
            'category-name' => 'required',
        ]);

        $customer_id = Category::where('id', $id)->first()->customer_id;
        if(isset($order)) {
            if (Category::where([
                    ['customer_id', $customer_id],
                    ['show_order', $order],
                    ['id', '!=', $id],
                ])->count() > 0) {
                return back()
                    ->with('warning', 'The Order is already set.');
            }
        }

        $rtl_direction = 0;
        if (isset($direction) && $direction == 'on')
            $rtl_direction = 1;

        Category::where('id', $id)->update([
            'name' => $name,
            'tags' => $tags,
            'name_second' => $name_ar,
            'tags_second' => $tags_ar,
            'show_order' => $order,
            'rtl_direction' => $rtl_direction
        ]);

        return back()
            ->with('success', 'You have successfully updated category.');
    }

    public function delCategory()
    {
        $id = request('id');
        Category::where('id', $id)->delete();
        Product::where('category_id', $id)->delete();

        return Utils::makeResponse();
    }

    public function toggleCategoryVisible()
    {
        $id = request('id');
        $show_flag = Category::where('id', $id)->first()->show_flag;

        Category::where('id', $id)->update([
            'show_flag' => 1 - $show_flag,
        ]);

        return Utils::makeResponse();
    }

    public function toggleCategoryAllVisible() {
        $user_type = session()->get('user-type');
        if ($user_type === 1 || $user_type === 2) {
            Category::query()->update(['show_flag' => 1]);
        } else {
            $customer_id = session()->get('user')->id;
            Category::where('customer_id', $customer_id)->update(['show_flag' => 1]);
        }
        return back();
    }

    public function toggleCategoryAllInvisible() {
        $user_type = session()->get('user-type');
        if ($user_type === 1 || $user_type === 2) {
            Category::query()->update(['show_flag' => 0]);
        } else {
            $customer_id = session()->get('user')->id;
            Category::where('customer_id', $customer_id)->update(['show_flag' => 0]);
        }
        return back();
    }

    public function setClientIdToSession() {
        $id = request('id');
        session()->put('selected-client-id', $id);
        return Utils::makeResponse();
    }

    public function deletePosition()
    {
        echo 'good!';
        die();
        $id = request('id');
        echo $id;
        die();
        $role = Role::find($id);

        Role::where('id', $id)->delete();

        return Utils::makeResponse();
    }
}
