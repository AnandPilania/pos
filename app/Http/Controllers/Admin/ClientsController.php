<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Clients;
use App\Http\Models\Invoices;
use App\Http\Models\Products;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;

class ClientsController
{
    public function index()
    {
        $clients = Clients::get();
        return view('admin.clients.list')
            ->with('clients', $clients)
            ->withTitle('Client List');
    }

    public function showAddPage()
    {
        return view('admin.clients.add')
            ->withTitle('Add Client');
    }

    public function showEditPage()
    {
        $id = request('id');
        $client = Clients::find($id);
        if ($client != null) {
            return view('admin.clients.edit')
                ->with('client', $client)
                ->withTitle('Edit Client');
        }
        return redirect()->route('admin.clients.show');
    }

    public function showDetailPage()
    {
        $id = request('client_id');
        $customer = Clients::find($id);

        $products = Products::where('customer_id', $id)->with('category', 'currency')->get();
        if ($customer != null) {
            return view('admin.clients.detail')
                ->with([
                    'customer' => $customer,
                    'products' => $products,
                ])
                ->withTitle('Client Detail');
        }
        return redirect()->route('admin.clients.show');
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

    public function showCustomerInvoicePrintPreviewPage()
    {

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
}
