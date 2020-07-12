@extends('layouts.admin')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection
@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <!-- Page JS Code -->
    <script type="text/javascript">
        $(document).ready(function () {
            window.page = new Pickitapps.pages.ClientsAdd();
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Add Client</h1>
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

                @if (isset($warning))
                    <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>There is no client yet. <br>You have to add client before add products.</strong>
                    </div>
                @endif

                <form action="{{route('admin.clients.add')}}" class="js-validation" method="POST">
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
                                           name="first-name" placeholder="First Name">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('last-name') is-invalid @enderror"
                                           name="last-name" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" placeholder="Email">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Birthday</label>
                                    <input type="text" class="js-datepicker form-control" name="birthday"
                                           data-week-start="0" data-autoclose="true" data-today-highlight="true"
                                           data-date-format="mm/dd/yyyy" placeholder="mm/dd/yyyy">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="d-block">Gender</label>
                                    <div
                                        class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input"
                                               id="example-radio-custom-inline1"
                                               name="gender" value="Male">
                                        <label class="custom-control-label"
                                               for="example-radio-custom-inline1">Male</label>
                                    </div>
                                    <div
                                        class="custom-control custom-radio custom-control-inline custom-control-primary">
                                        <input type="radio" class="custom-control-input"
                                               id="example-radio-custom-inline2"
                                               name="gender" value="Female">
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
                                           placeholder="+18003030203">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="dm-project-new-name">
                                        Company
                                    </label>
                                    <input type="text" class="form-control" name="company" placeholder="Company">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dm-project-new-name">
                                    Address
                                </label>
                                <input type="text" class="form-control" name="address" placeholder="Address">
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="dm-project-new-name">
                                        City
                                    </label>
                                    <input type="text" class="form-control" name="city" placeholder="City">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="dm-project-new-name">
                                        State
                                    </label>
                                    <input type="text" class="form-control" name="state" placeholder="State">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="dm-project-new-name">
                                        Zip Code
                                    </label>
                                    <input type="text" class="form-control" name="zip-code" placeholder="Zip Code">
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="content-heading">Company Details</h2>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="form-group">
                                <label>Name of person</label>
                                <input type="text" class="form-control" name="contact-person"
                                       placeholder="Name of person">
                            </div>
                            <div class="form-group">
                                <label>Contact Email address</label>
                                <input type="text" class="form-control" name="contact-email"
                                       placeholder="Contact Email address">
                            </div>
                            <div class="form-group">
                                <label>Contact Phone number</label>
                                <input type="text" class="form-control" name="contact-phone"
                                       placeholder="Contact Phone number">
                            </div>
                        </div>
                    </div>
                    <h2 class="content-heading">Subscription Details</h2>
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="form-group">
                                <label>Subscription Name</label> <span class="text-danger">*</span>
                                <select class="custom-select" name="subscription">
                                    <option value="" disabled="disabled" selected>Select a subscription</option>
                                    @foreach($subscriptions as $subscription)
                                        <option
                                            value="{{$subscription->id}}">{{$subscription->name . ' - ' . $subscription->price . ''}}</option>
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
                                           data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text font-w600">
                                            <i class="fa fa-fw fa-arrow-right"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" name="expire-date" placeholder="To"
                                           data-week-start="0" data-autoclose="true" data-today-highlight="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    Discount Price (for free tutorial usage)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            KWD
                                        </span>
                                    </div>
                                    <input type="text" class="form-control text-center" name="discount"
                                           placeholder="100.000">
                                </div>
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
                                    <i class="fa fa-times-circle mr-1"></i> Back
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
@endsection
