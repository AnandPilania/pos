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
     class="enable-page-overlay side-scroll page-header-fixed page-header-dark main-content-narrow">

    <!-- Header -->
    <header id="page-header" style="background-color: {{$theme->banner_color}} !important;">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div>
                <div class="content-header header-div-logo">
                    <!-- Logo -->
                    <a class="font-w600 font-size-lg text-white" href="{{url('/restaurant').'/'.$theme->id}}">
                        <img src="{{asset('/media/company_logos/').'/'.$theme->company_logo}}" style="max-height: 60px;">
                    </a>
                    <!-- END Logo -->
                </div>
            </div>
            <div>
                <h1 class="front-header-text">Welcome to Pick it System</h1>
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div>
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-language d-sm-none"></i>
                        <span class="d-none d-sm-inline-block">{{$lang == 'en' ? 'English' : 'عربي'}}</span>
                        <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                        <div class="p-2">
                            <a class="dropdown-item {{$lang == 'en' ? 'active' : ''}}" href="javascript:onLanguageChange('en');">
                                English
                            </a>
                            <a class="dropdown-item {{$lang == 'ar' ? 'active' : ''}}" href="javascript:onLanguageChange('ar');">
                                عربي
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END User Dropdown -->

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
    <main id="main-container" style="background-color: {{$theme->product_background_color}} !important;">
        @yield('content')
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-body-light" style="background-color: {{$theme->banner_color}} !important; color: {{$theme->font_color}};">
        <div class="content py-0">
            <div class="row font-size-sm">
                <div class="col-sm-6 order-sm-2 mb-1 mb-sm-0 text-center text-sm-right">
                    Crafted with <i class="fa fa-heart text-danger"></i> by <a class="font-w600"
                                                                               href="https://www.mrfocuskw.com"
                                                                               target="_blank">Mr focus</a>
                </div>
                <div class="col-sm-6 order-sm-1 text-center text-sm-left">
                    <a class="font-w600" href="{{url('/')}}" target="_blank">Pick it</a> &copy; <span
                        data-toggle="year-copy">2019</span>
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
