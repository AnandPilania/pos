@extends('layouts.client')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Client Information</h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-content">
                <form>
                    <h2 class="content-heading">Company Details</h2>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" disabled
                                           value="{{$client->first_name}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control"
                                           disabled value="{{$client->last_name}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" disabled
                                           value="{{$client->email}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Birthday
                                    </label>
                                    <input type="text" class="form-control" disabled
                                           value="{{ date('m/d/Y', strtotime($client->birthday)) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="dm-project-new-name">
                                        PhoneNumber <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" disabled
                                           value="{{$client->phonenumber}}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="d-block">Gender</label>
                                    <div
                                        class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input"
                                               id="example-radio-custom-inline1"
                                               name="gender" value="Male"
                                               @if($client->gender == 'Male') checked @endif disabled>
                                        <label class="custom-control-label"
                                               for="example-radio-custom-inline1">Male</label>
                                    </div>
                                    <div
                                        class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input"
                                               id="example-radio-custom-inline2"
                                               name="gender" value="Female"
                                               @if($client->gender == 'Female') checked @endif disabled>
                                        <label class="custom-control-label"
                                               for="example-radio-custom-inline2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" disabled
                                       value="{{$client->company}}">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" disabled
                                       value="{{$client->address}}">
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" disabled
                                           value="{{$client->city}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" disabled
                                           value="{{$client->state}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Zip Code</label>
                                    <input type="text" class="form-control" disabled
                                           value="{{$client->zipcode}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Business Type</label>
                                <select class="custom-select" name="business-type" disabled>
                                    <option value="" disabled="disabled" selected></option>
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
                                <input type="text" class="form-control" disabled
                                       value="{{$client->contact_person}}">
                            </div>
                            <div class="form-group">
                                <label>Contact Email address</label>
                                <input type="text" class="form-control" disabled
                                       value="{{$client->contact_email}}">
                            </div>
                            <div class="form-group">
                                <label>Contact Phone number</label>
                                <input type="text" class="form-control" disabled
                                       value="{{$client->contact_phone}}">
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
                </form>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
