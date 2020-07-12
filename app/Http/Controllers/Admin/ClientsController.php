<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\Client;
use App\Http\Models\Invoice;
use App\Http\Models\Product;
use App\Http\Models\Subscription;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;

class ClientsController
{
    public function index()
    {
        $clients = Client::with('subscriptions')->get();
        return view('admin.clients.list')
            ->with('clients', $clients)
            ->withTitle('Client List');
    }

    public function showAddPage()
    {
        $subscriptions = Subscription::get();
        return view('admin.clients.add')
            ->with('subscriptions', $subscriptions)
            ->withTitle('Add Client');
    }

    public function showEditPage()
    {
        $id = request('id');
        $client = Client::find($id);
        $subscriptions = Subscription::get();
        if ($client != null) {
            return view('admin.clients.edit')
                ->with([
                    'client' => $client,
                    'subscriptions' => $subscriptions
                ])
                ->withTitle('Edit Client');
        }
        return redirect()->route('admin.clients.show');
    }

    public function showDetailPage()
    {
        $id = request('client_id');
        $customer = Client::find($id);

        $products = Product::where('customer_id', $id)->with('category', 'currency')->get();
        if ($customer != null) {
            return view('admin.clients.detail')
                ->with([
                    'customer' => $customer,
                    'products' => $products,
                    'client_id' => $id
                ])
                ->withTitle('Client Detail');
        }
        return redirect()->route('admin.clients.show');
    }

    public function add()
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
        $contact_person = request('contact-person');
        $contact_email = request('contact-email');
        $contact_phone = request('contact-phone');
        $subscription = request('subscription');
        $start_date = request('start-date');
        $expire_date = request('expire-date');
        $discount = request('discount');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'email' => 'required|email|unique:clients',
            'password' => 'required',
            'phone-number' => 'required',
            'subscription' => 'required',
            'start-date' => 'required|date',
            'expire-date' => 'required|date',
        ]);

        $birthday = strtotime($birthday);
        $birthday = date('Y-m-d', $birthday);

        $start_date = strtotime($start_date);
        $start_date = date('Y-m-d', $start_date);

        $expire_date = strtotime($expire_date);
        $expire_date = date('Y-m-d', $expire_date);

        $client = new Client();
        $client->first_name = $first_name;
        $client->last_name = $last_name;
        $client->email = $email;
        $client->password = bcrypt($password);
        $client->birthday = $birthday;
        $client->gender = $gender;
        $client->phonenumber = $phonenumber;
        $client->company = $company;
        $client->address = $address;
        $client->city = $city;
        $client->state = $state;
        $client->zipcode = $zipcode;
        $client->start_date = $start_date;
        $client->expire_date = $expire_date;
        $client->contact_person = $contact_person;
        $client->contact_email = $contact_email;
        $client->contact_phone = $contact_phone;

        $client->save();

        $client->subscriptions()->attach($subscription);

        $invoice = new Invoice();
        $invoice->customer_id = $client->id;
        $invoice->subscription_id = $subscription;
        $invoice->start_date = $start_date;
        $invoice->expire_date = $expire_date;

        $numberOfMonths = Utils::getNumberOfMonths($start_date, $expire_date);
        if ($numberOfMonths == 0) $numberOfMonths = 1;

        $sb = Subscription::find($subscription);
        $invoice->subscription_months = $numberOfMonths;
        $invoice->subscription_total_price = $sb->price * $numberOfMonths;
        $invoice->discount = $discount;
        $invoice->price = ($sb->price * $numberOfMonths - $discount) < 0 ? 0 : ($sb->price * $numberOfMonths - $discount);

        $invoice->save();

        Client::where('id', $client->id)->update([
            'current_invoice_id' => $invoice->id
        ]);

        return back()
            ->with('success', 'Successfully added.');
    }

    public function edit()
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
            'email' => 'required|email|unique:clients,email,' . $id,
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
            $invoice = new Invoice();
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
            Invoice::where('id', Customers::where('id', $id)->first()->current_invoice_id)->update([
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

    public function delete()
    {
        $id = request('id');
        $user = Client::find($id);
        $user->subscriptions()->detach();
        $user->sanctions()->detach();
        $user->delete();

        return Utils::makeResponse();
    }

    public function toggleActive()
    {
        $id = request('id');
        $active = Client::where('id', $id)->first()->active;

        Client::where('id', $id)->update([
            'active' => 1 - $active,
        ]);

        return Utils::makeResponse();
    }

    public function printCustomerInvoice()
    {
        $id = request('id');
        $customer = Client::find($id);
        $invoices = Invoice::where('customer_id', $id)->get();
        $total = Invoice::where('customer_id', $id)->sum('price');

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
        $customer = Client::find($id);
        $invoices = Invoice::where('customer_id', $id)->get();
        $total = Invoice::where('customer_id', $id)->sum('price');

        return view('admin.clients.invoice_print_preview')->with([
            'customer' => $customer,
            'invoices' => $invoices,
            'total' => $total,
        ]);
    }
}
