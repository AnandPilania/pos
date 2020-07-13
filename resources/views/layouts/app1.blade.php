<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Pick it</title>

    <meta name="description" content="Welcome to Pick it system">
    <meta name="author" content="Mr focus">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
    <link rel="stylesheet" href="{{ mix('css/pickitapps.app.css') }}">

    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
<!-- <link rel="stylesheet" href="{{ mix('css/themes/xwork.css') }}"> -->

@yield('css_after')

<!-- Scripts -->
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
</head>
<body>

<div id="page-container"
     class="enable-page-overlay side-scroll page-header-fixed page-header-dark main-content-narrow frontend2-container frontend1-container">

    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div>
                <div class="header-div-logo">
                    <!-- Logo -->
                    <a class="font-w600 font-size-lg text-white" href="{{url('/')}}">
                        <img src="{{asset('/media/logos/logo.png')}}" class="header-logo-img">
                    </a>
                    <!-- END Logo -->
                </div>
            </div>
            <!-- END Left Section -->


            <!-- Right Section -->
            <div>

                <nav id="sidebar" aria-label="Main Navigation">
                    <!-- Side Navigation -->
                    <div class="content-side content-side-full">
                        <ul class="nav-main main-menu">
                            <li><a href="{{url('/')}}" class="{{ request()->is('/') ? ' active' : '' }}">Home</a></li>
{{--                            <li><a href="{{url('/products')}}" class="{{ request()->is('products*') ? ' active' : '' }}">Our Clients Shop</a></li>--}}
                            <li><a href="{{url('/contact')}}" class="{{ request()->is('contact*') ? ' active' : '' }}">Contact Us</a></li>
                            <li><a href="https://my.pickitapps.com" class="{{ request()->is('login*') ? ' active' : '' }}">Log In</a></li>
                        </ul>
                    </div>
                    <!-- END Side Navigation -->
                </nav>

                <div>
                    <div class="content content-full">
                        <div class="d-flex align-items-sm-center search-bar-div" style="justify-content: space-between;">
                            <div class="category-list-nav-bar" >
                                <div class="d-flex">
                                    <ul class="nav-main main-menu">
                                        <li><a href="{{url('/')}}" class="{{ request()->is('/') ? ' active' : '' }}">Home</a></li>
{{--                                        <li><a href="{{url('/products')}}" class="{{ request()->is('products*') ? ' active' : '' }}">Our Clients Shop</a></li>--}}
                                        <li><a href="{{url('/contact')}}" class="{{ request()->is('contact*') ? ' active' : '' }}">Contact Us</a></li>
                                        <li><a href="https://my.pickitapps.com" class="{{ request()->is('login*') ? ' active' : '' }}">Log In</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="category-list-nav-bar-button">
                                <button type="button" class="btn btn-dual mr-1" data-toggle="layout" data-action="sidebar_toggle" style="color: white;">
                                    <i class="fa fa-fw fa-bars"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Loader -->
        <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-primary-darker">
            <div class="content-header">
                <div class="w-100 text-center">
                    <i class="fa fa-fw fa-2x fa-sun fa-spin text-white"></i>
                </div>
            </div>
        </div>
        <!-- END Header Loader -->
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container" >
        @yield('content')
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-body-light" >
        <div class="content py-0">
            <div class="row font-size-sm footer-column-menu">
                <div class="col-md-3 col-sm-6">
                    <div class="logo"><img src="{{asset('/media/logos/logo.png')}}"></div>
                    <ul class="footer-column-menu-ul">
                        <li><a href="tel:+96566298383"><i class="si si-call-out"></i>+965 66298383</a></li>
                        <li><a href="mailto:info@pickitapps.com"><i class="si si-envelope"></i>info@pickitapps.com</a></li>
                        <li><a href="{{url('/')}}"><i class="si si-pointer"></i> Kuwait, sharq</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="name">Explore</div>
                    <ul class="footer-column-menu-ul">
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{url('/')}}">Shops</a></li>
                        <li><a href="{{url('/')}}">Contact Us</a></li>
                        <li><a href="{{url('/login')}}">Join US</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="name">Services</div>
                    <ul class="footer-column-menu-ul">
                        <li><a href="{{url('/')}}">Speed Optimization</a></li>
                        <li><a href="{{url('/')}}">Marketing Analysis</a></li>
                        <li><a href="{{url('/')}}">SEO and Backlinks</a></li>
                        <li><a href="{{url('/')}}">Content Marketing</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="name">Links</div>
                    <ul class="footer-column-menu-ul">
                        <li><a href="{{url('/')}}">Help</a></li>
                        <li><a href="{{url('/')}}">Support</a></li>
                        <li><a href="{{url('/')}}">Clients</a></li>
                        <li><a href="{{url('/')}}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 social-links">
                    <div class="">
                        <a href="https://instagram.com/pickit.app?igshid=1pzf2ajb5wv1x"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="{{url('/')}}"><i class="si si-social-facebook fa-2x"></i></a>
                        <a href="{{url('/')}}"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="copy-right">
                <div class="">
                    &copy; copyright <span data-toggle="year-copy">2019</span> by <a class="font-w600" href="{{url('/')}}" target="_blank">MrFocus.com</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<!-- Pickitapps Core JS -->
<script src="{{ mix('js/pickitapps.app.js') }}"></script>

<!-- Laravel Scaffolding JS -->
<script src="{{ mix('js/laravel.app.js') }}"></script>

<script>window.baseUrl = '{{url('/')}}';</script>
@yield('js_after')
</body>
</html>
