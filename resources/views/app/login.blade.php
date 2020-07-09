@extends('layouts.app2')

@section('css_before')
    <!-- Page JS Plugins CSS -->

@endsection


@section('content')
    <!-- Hero -->
    <div class="sub-header">
        Sign up
    </div>
    <div class="content">
        <div class="row no-gutters justify-content-center" >
            <div class="col-sm-12 col-xl-6" data-aos="fade-right">
                <div class="offset-md-1 col-sm-10 col-xl-10">
                    <div class="form-header-text">
                        Sign up for your account
                    </div>
                    <form class="js-validation-signup" action="be_pages_auth_all.html" method="post">
                        <div class="py-3">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg form-control-alt" id="signup-username" name="signup-username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg form-control-alt" id="signup-email" name="signup-email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg form-control-alt" id="signup-password" name="signup-password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg form-control-alt" id="signup-password-confirm" name="signup-password-confirm" placeholder="Password Confirm">
                            </div>
                        </div>
                        <div class="form-group auth-form-bottom">
                            <div class="custom-control custom-checkbox custom-control-primary">
                                <input type="checkbox" class="custom-control-input" id="signup-terms" name="signup-terms">
                                <label class="custom-control-label" for="signup-terms">I have read and agreed with the <a href="#" class="terms-and-cond">Terms &amp; Conditions</a></label>
                            </div>
                            <button type="submit" class="btn btn-block btn-hero-info">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6" data-aos="fade-left">
                <div class="offset-md-1 col-sm-9 col-xl-9">
                    <div class="form-header-text">
                        Sign in
                    </div>
                    <form class="js-validation-signin" action="be_pages_auth_all.html" method="post">
                        <div class="py-3">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg form-control-alt" id="login-username" name="login-username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg form-control-alt" id="login-password" name="login-password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group auth-form-bottom">
                            <div class="custom-control custom-checkbox custom-control-primary">
                                <input type="checkbox" class="custom-control-input" id="remember-password" name="remember-password">
                                <label class="custom-control-label" for="remember-password">Remeber Password</label>
                            </div>
                            <button type="submit" class="btn btn-block btn-hero-info">Sign In</button>
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
    <script>jQuery(function(){ Dashmix.helpers('select2'); });</script>

@endsection
