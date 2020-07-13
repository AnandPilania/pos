<?php


namespace App\Http\Controllers\Admin;


use App\Http\Models\BusinessType;
use App\Http\Models\Category;
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
        if(!auth()->user()->can('client-list')) {
            return back();
        }

        $clients = Client::with('subscriptions')->get();
        return view('admin.clients.list')
            ->with('clients', $clients)
            ->withTitle('Client List');
    }

    public function showAddPage()
    {
        if(!auth()->user()->can('client-create')) {
            return back();
        }

        $subscriptions = Subscription::get();
        $businessTypes = BusinessType::get();
        return view('admin.clients.add')
            ->with([
                'subscriptions' => $subscriptions,
                'businessTypes' => $businessTypes
            ])
            ->withTitle('Add Client');
    }

    public function showEditPage()
    {
        if(!auth()->user()->can('client-edit')) {
            return back();
        }

        $id = request('id');
        $client = Client::find($id);
        $subscriptions = Subscription::get();
        $invoice = Invoice::find($client->current_invoice_id);
        $businessTypes = BusinessType::get();
        if ($client != null) {
            return view('admin.clients.edit')
                ->with([
                    'client' => $client,
                    'subscriptions' => $subscriptions,
                    'invoice' => $invoice,
                    'businessTypes' => $businessTypes
                ])
                ->withTitle('Edit Client');
        }
        return redirect()->route('admin.clients.show');
    }

    public function showOverviewPage()
    {
        if(!auth()->user()->can('client-list')) {
            return back();
        }

        $client_id = request('client_id');

        $products = Product::where('customer_id', $client_id)->count();
        $categories = Category::where('customer_id', $client_id)->count();

        return view('admin.clients.dashboard')->with([
            'products' => $products,
            'categories' => $categories,
            'client_id' => $client_id
        ]);
    }

    public function showInformationPage()
    {
        if(!auth()->user()->can('client-list')) {
            return back();
        }

        $id = request('client_id');
        $client = Client::find($id);
        $subscriptions = Subscription::get();
        $invoice = Invoice::find($client->current_invoice_id);
        $businessTypes = BusinessType::get();
        if ($client != null) {
            return view('admin.clients.detail')
                ->with([
                    'client' => $client,
                    'client_id' => $id,
                    'subscriptions' => $subscriptions,
                    'invoice' => $invoice,
                    'businessTypes' => $businessTypes
                ])
                ->withTitle('Client Detail');
        }
        return redirect()->route('admin.clients.show');
    }

    public function add()
    {
        if(!auth()->user()->can('client-create')) {
            return back();
        }

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
        $business = request('business-type');
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
        $client->business_type_id = $business;
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
            'current_invoice_id' => $invoice->id,
            'price' => $invoice->price
        ]);

        return back()
            ->with('success', 'Successfully added.');
    }

    public function edit()
    {
        if(!auth()->user()->can('client-edit')) {
            return back();
        }

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
        $business = request('business-type');
        $contact_person = request('contact-person');
        $contact_email = request('contact-email');
        $contact_phone = request('contact-phone');

        request()->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'phone-number' => 'required',
            'email' => 'required|email|unique:clients,email,' . $id,
        ]);

        $birthday = strtotime($birthday);
        $birthday = date('Y-m-d', $birthday);

        $updateArray = [
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
            'business_type_id' => $business,
            'contact_email' => $contact_email,
            'contact_phone' => $contact_phone,
            'contact_person' => $contact_person
        ];

        if ($password != '') {
            $updateArray['password'] = bcrypt($password);
        }

        Client::where('id', $id)->update($updateArray);

        return back()
            ->with('success', 'Successfully updated.');
    }

    public function resuscitateCustomer()
    {
        if(!auth()->user()->can('client-edit')) {
            return back();
        }

        $id = request('id');
        $start_date = request('start-date');
        $expire_date = request('expire-date');
        $discount = request('discount');
        $subscription = request('subscription');
        $add_flag = request('add_flag');

        request()->validate([
            'start-date' => 'required|date',
            'expire-date' => 'required|date',
            'subscription' => 'required',
        ]);

        $start_date = date('Y-m-d', strtotime($start_date));
        $expire_date = date('Y-m-d', strtotime($expire_date));

        $numberOfMonths = Utils::getNumberOfMonths($start_date, $expire_date);
        if ($numberOfMonths == 0) $numberOfMonths = 1;

        $sb = Subscription::find($subscription);
        $total_price = $sb->price * $numberOfMonths;

        if ($add_flag == 1) {

            $invoice = new Invoice();
            $invoice->customer_id = $id;
            $invoice->subscription_id = $subscription;
            $invoice->start_date = $start_date;
            $invoice->expire_date = $expire_date;

            $invoice->subscription_months = $numberOfMonths;
            $invoice->subscription_total_price = $total_price;
            $invoice->discount = $discount;
            $invoice->price = ($total_price - $discount) < 0 ? 0 : ($total_price - $discount);

            $invoice->save();

            Client::where('id', $id)->update([
                'start_date' => $start_date,
                'expire_date' => $expire_date,
                'price' => $invoice->price,
                'current_invoice_id' => $invoice->id,
            ]);
        } else {
            Invoice::where('id', Client::find($id)->current_invoice_id)
                ->update([
                    'start_date' => $start_date,
                    'expire_date' => $expire_date,
                    'discount' => $discount,
                    'price' => ($total_price - $discount) < 0 ? 0 : ($total_price - $discount),
                    'subscription_id' => $subscription,
                    'subscription_months' => $numberOfMonths,
                    'subscription_total_price' => $total_price
                ]);

            Client::where('id', $id)->update([
                'start_date' => $start_date,
                'expire_date' => $expire_date,
                'price' => ($total_price - $discount) < 0 ? 0 : ($total_price - $discount),
            ]);
        }

        $client = Client::find($id);
        $client->subscriptions()->sync($subscription);

        return Utils::makeResponse();
    }

    public function delete()
    {
        if(!auth()->user()->can('client-delete')) {
            return back();
        }

        $id = request('id');
        $user = Client::find($id);
        $user->subscriptions()->detach();
        $user->sanctions()->detach();
        $user->delete();

        return Utils::makeResponse();
    }

    public function toggleActive()
    {
        if(!auth()->user()->can('client-edit')) {
            return back();
        }

        $id = request('id');
        $active = Client::where('id', $id)->first()->active;

        Client::where('id', $id)->update([
            'active' => 1 - $active,
        ]);

        return Utils::makeResponse();
    }

    public function printCustomerInvoice()
    {
        if(!auth()->user()->can('client-list')) {
            return back();
        }

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
        if(!auth()->user()->can('client-list')) {
            return back();
        }

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
