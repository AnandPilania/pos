@extends('layouts.app2')

@section('css_before')
    <!-- Page JS Plugins CSS -->
@endsection


@section('content')
    <!-- Hero -->

    <div class="sub-header">
        Contact Us
    </div>
    <div class="content">
        <div class="row no-gutters justify-content-center" data-aos="fade-down">
            <div class="col-sm-12 col-xl-8">
                <div class="offset-md-1 col-sm-10 col-xl-10">
                    <div class="form-header-text text-center">
                        <span class="text-uppercase font-size-h6">Contact with us</span>
                        <p class="text-uppercase">Write Us a Message</p>
                        <p class="font-w700 font-size-h5 text-muted">We are committed to providing our customers with exceptional service while offering our employees the best training.</p>
                    </div>
                    <form class="js-validation-signup" action="be_pages_auth_all.html" method="post">
                        <div class="py-3">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 form-group ">
                                    <input type="text" class="form-control form-control-lg form-control-alt" placeholder="First Name">
                                </div>
                                <div class="col-sm-12 col-md-6 form-group ">
                                    <input type="text" class="form-control form-control-lg form-control-alt" placeholder="Last Name">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 form-group ">
                                    <input type="email" class="form-control form-control-lg form-control-alt" placeholder="Email">
                                </div>
                                <div class="col-sm-12 col-md-6 form-group ">
                                    <input type="ext" class="form-control form-control-lg form-control-alt" placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control form-control-lg form-control-alt" rows="7"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-hero-lg btn-hero-info btn-pickit">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- END Page Content -->
@endsection

@section('js_after')
    <!-- Page JS Plugins -->
    <!-- Page JS Code -->

    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function(){ Pickitapps.helpers('select2'); });</script>

@endsection
