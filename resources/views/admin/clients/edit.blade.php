@extends('layouts.admin')
@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection
@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            window.page = new Pickitapps.pages.ClientsEdit();
        });
    </script>
@endsection
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Edit Client</h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-content">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{route('admin.clients.edit', $client->id)}}" class="js-validation" method="POST">
                    @csrf
                    <h2 class="content-heading">Company Details</h2>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('first-name') is-invalid @enderror"
                                           name="first-name" placeholder="First Name"
                                           value="{{$client->first_name}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('last-name') is-invalid @enderror"
                                           name="last-name" placeholder="Last Name"
                                           value="{{$client->last_name}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" placeholder="Email"
                                           value="{{$client->email}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        Birthday
                                    </label>
                                    <input type="text" class="js-datepicker form-control" name="birthday"
                                           data-week-start="0" data-autoclose="true" data-today-highlight="true"
                                           data-date-format="mm/dd/yyyy" placeholder="mm/dd/yyyy"
                                           value="{{ date('m/d/Y', strtotime($client->birthday)) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="d-block">Gender</label>
                                    <div
                                        class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input"
                                               id="example-radio-custom-inline1"
                                               name="gender" value="Male"
                                               @if($client->gender == 'Male') checked @endif >
                                        <label class="custom-control-label"
                                               for="example-radio-custom-inline1">Male</label>
                                    </div>
                                    <div
                                        class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input"
                                               id="example-radio-custom-inline2"
                                               name="gender" value="Female"
                                               @if($client->gender == 'Female') checked @endif>
                                        <label class="custom-control-label"
                                               for="example-radio-custom-inline2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="dm-project-new-name">
                                        PhoneNumber <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('phone-number') is-invalid @enderror"
                                           name="phone-number"
                                           placeholder="+18003030203" value="{{$client->phonenumber}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="dm-project-new-name">
                                        Company
                                    </label>
                                    <input type="text" class="form-control" name="company" placeholder="Company"
                                           value="{{$client->company}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dm-project-new-name">
                                    Address
                                </label>
                                <input type="text" class="form-control" name="address" placeholder="Address"
                                       value="{{$client->address}}">
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="dm-project-new-name">
                                        City
                                    </label>
                                    <input type="text" class="form-control" name="city" placeholder="City"
                                           value="{{$client->city}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="dm-project-new-name">
                                        State
                                    </label>
                                    <input type="text" class="form-control" name="state" placeholder="State"
                                           value="{{$client->state}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="dm-project-new-name">
                                        Zip Code
                                    </label>
                                    <input type="text" class="form-control" name="zip-code" placeholder="Zip Code"
                                           value="{{$client->zipcode}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Business Type</label>
                                <select class="custom-select" name="business-type">
                                    <option value="" disabled="disabled" selected>Select a subscription</option>
                                    @foreach($businessTypes as $e)
                                        <option
                                            value="{{$e->id}}"
                                            @if($client->business_type_id == $e->id) selected @endif
                                        >{{$e->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <h2 class="content-heading">Contact Details</h2>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="form-group">
                                <label>Name of person</label>
                                <input type="text" class="form-control" name="contact-person"
                                       value="{{$client->contact_person}}" placeholder="Name of person">
                            </div>
                            <div class="form-group">
                                <label>Contact Email address</label>
                                <input type="text" class="form-control" name="contact-email"
                                       value="{{$client->contact_email}}" placeholder="Contact Email address">
                            </div>
                            <div class="form-group">
                                <label>Contact Phone number</label>
                                <input type="text" class="form-control" name="contact-phone"
                                       value="{{$client->contact_phone}}" placeholder="Contact Phone number">
                            </div>
                        </div>
                    </div>
                    <h2 class="content-heading">Subscription Details</h2>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="form-group">
                                <label>Subscription Name</label> <span class="text-danger">*</span>
                                <select class="custom-select" name="subscription" disabled>
                                    <option value="" disabled="disabled"
                                            @if(!isset($invoice->subscription_id)) selected @endif>Select a subscription
                                    </option>
                                    @foreach($subscriptions as $subscription)
                                        <option
                                            value="{{$subscription->id}}"
                                            @if(isset($invoice->subscription_id) && $invoice->subscription_id == $subscription->id) selected @endif
                                        >{{$subscription->name . ' - ' . $subscription->price . ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    Start Date ~ Expire Date <span class="text-danger">*</span>
                                </label>
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy"
                                     data-week-start="0" data-autoclose="true" data-today-highlight="true">
                                    <input type="text" class="form-control" name="start-date" placeholder="From"
                                           data-week-start="1" data-autoclose="true" data-today-highlight="true"
                                           value="{{ date('m/d/Y', strtotime($client->start_date)) }}"
                                           disabled="disabled">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text font-w600">
                                            <i class="fa fa-fw fa-arrow-right"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" name="expire-date" placeholder="To"
                                           data-week-start="0" data-autoclose="true" data-today-highlight="true"
                                           value="{{ date('m/d/Y', strtotime($client->expire_date)) }}"
                                           disabled="disabled">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    Discount Price
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            KWD
                                        </span>
                                    </div>
                                    <input type="text" class="form-control text-center" name="discount"
                                           placeholder="00.000" value="{{$invoice->discount ?? 0}}" disabled="disabled">
                                </div>
                            </div>
                            <div class="d-flex flex-column-reverse form-group">
                                <div class="d-flex flex-row-reverse">
                                    <a href="javascript:page.onCancelButtonClicked('{{ date('m/d/Y', strtotime($client->start_date)) }}',
                                    '{{ date('m/d/Y', strtotime($client->expire_date)) }}', {{$invoice->discount ?? 0}}, {{$invoice->subscription_id ?? 0}});"
                                       class="btn btn-danger"
                                       id="cancel-button" style="display: none">Cancel</a>
                                    <a href="javascript:;" class="btn btn-primary mr-2" id="save-button"
                                       style="display: none">Save</a>
                                    <a href="javascript:page.onEditButtonClicked();" class="btn btn-success"
                                       id="edit-button"><i class="fa fa-pencil-alt"></i> Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="content-heading">WebMenu Details</h2>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="dm-project-new-name">
                                    URL (Frontend Site URL)
                                </label>
                                <label class="form-control">{{url('/restaurant').'/'.$client->id}}</label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="row push">
                        <div class="col-lg-8 col-xl-5">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-check-circle mr-1"></i> Submit
                                </button>
                                <a class="btn btn-danger" href="{{route('admin.clients.show')}}">
                                    <i class="fa fa-times-circle mr-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END Submit -->
                </form>
            </div>
        </div>
    </div>
    <!-- END Page Content -->

    <!-- Fade In Block Modal -->
    <div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Please confirm</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <p>Do you want add new history of invoice of this customer or just edit this invoice ?</p>
                    </div>
                    <div class="block-content block-content-full text-right bg-light">
                        <a href="javascript:page.resuscitateCustomer({{$client->id}}, 1);"
                           class="btn btn-sm btn-primary">Add
                            new invoice</a>
                        <a href="javascript:page.resuscitateCustomer({{$client->id}}, 0);"
                           class="btn btn-sm btn-success">Edit
                            current invoice</a>
                        <button class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Fade In Block Modal -->
@endsection
