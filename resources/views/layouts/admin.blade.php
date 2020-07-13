<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>{{isset($title) ? $title : $APP_NAME}}</title>

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
    <link rel="stylesheet" href="{{ mix('css/pickitapps.admin.css') }}">

    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
    <link rel="stylesheet" href="{{ mix('css/themes/xwork.css') }}">
@yield('css_after')

<!-- Scripts -->
    @routes
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
</head>
<body>
<!-- Page Container -->

<div id="page-container"
     class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-dark main-content-narrow">
    <!-- Sidebar -->
    <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header -->
        <div class="bg-header-dark">
            <div class="content-header bg-white-10">
                <!-- Logo -->
                <a class="link-fx font-w600 font-size-lg text-white" href="{{route('admin.home')}}">
                    <span class="smini-visible">
                        <span class="text-white-75">P</span><span class="text-white">i</span>
                    </span>
                    <span class="smini-hidden">
                        <span class="text-white-75">Pick</span><span class="text-white">it</span></span>
                </a>
                <!-- END Logo -->

                <!-- Options -->
                <div>
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close"
                       href="javascript:void(0)">
                        <i class="fa fa-times-circle"></i>
                    </a>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Options -->
            </div>
        </div>
        <!-- END Side Header -->

        <!-- User Info -->
        <div class="smini-hidden">
            <div class="content-side content-side-full bg-black-10 d-flex align-items-center">
                <a class="img-link d-inline-block" href="{{route('admin.profile.show')}}">
                    <img class="img-avatar img-avatar48 img-avatar-thumb"
                         src="{{asset('media/avatars').'/'.auth()->user()->avatar}}" alt="">
                </a>
                <div class="ml-3">
                    <a class="font-w600 text-dual"
                       href="{{route('admin.profile.show')}}">{{auth()->user()->first_name.' '.auth()->user()->last_name}}</a>
                    <div class="font-size-sm font-italic text-dual">
                        @foreach(auth()->user()->roles as $role)
                            {{$role->name}} <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- END User Info -->

        <!-- Side Navigation -->
        <div class="content-side content-side-full">
            <ul class="nav-main">
                <li class="nav-main-heading">DASHBOARD</li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('*dashboard*') ? 'active' : ''}}"
                       href="{{route('admin.dashboard')}}">
                        <i class="nav-main-link-icon si si-pie-chart"></i>
                        <span class="nav-main-link-name">Overview</span>
                    </a>
                </li>

                <li class="nav-main-heading">Employee Management</li>
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('*employees*') ? ' active' : '' }}"
                       href="{{route('admin.employees.show')}}">
                        <i class="nav-main-link-icon far fa-user-circle"></i>
                        <span class="nav-main-link-name">Employees</span>
                    </a>
                </li>
                @can('position-list')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('*positions*') ? ' active' : '' }}"
                       href="{{route('admin.positions.show')}}">
                        <i class="nav-main-link-icon far fa-user-circle"></i>
                        <span class="nav-main-link-name">Positions</span>
                    </a>
                </li>
                @endcan

                <li class="nav-main-heading">Client Management</li>
                @can('client-list')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('*clients*') ? ' active' : '' }}"
                       href="{{route('admin.clients.show')}}">
                        <i class="nav-main-link-icon si si-emoticon-smile"></i>
                        <span class="nav-main-link-name">Clients</span>
                    </a>
                </li>
                @endcan
                @can('business-type-list')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('*business-types*') ? ' active' : '' }}"
                       href="{{route('admin.business-types.show')}}">
                        <i class="nav-main-link-icon si si-emoticon-smile"></i>
                        <span class="nav-main-link-name">Business Types</span>
                    </a>
                </li>
                @endcan
                @can('subscription-list')
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('*subscriptions*') ? ' active' : '' }}"
                       href="{{route('admin.subscriptions.show')}}">
                        <i class="nav-main-link-icon si si-emoticon-smile"></i>
                        <span class="nav-main-link-name">Subscriptions</span>
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        <!-- END Side Navigation -->
    </nav>
    <!-- END Sidebar -->

    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div>
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                <button type="button" class="btn btn-dual mr-1" data-toggle="layout" data-action="sidebar_toggle">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->

            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div>
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-user d-sm-none"></i>
                        <span class="d-none d-sm-inline-block">Hi, {{ auth()->user()->first_name }}</span>
                        <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                        <div class="bg-primary-darker rounded-top font-w600 text-white text-center p-3">
                            User Options
                        </div>
                        <div class="p-2">
                            <a class="dropdown-item" href="{{route('admin.profile.show')}}">
                                <i class="far fa-fw fa-user mr-1"></i> Profile
                            </a>
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('admin.logout')}}">
                                <i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> Sign Out
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
    <main id="main-container">
        @yield('content')
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-body-light">
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
<script src="{{ mix('js/pickitapps.admin.js') }}"></script>

<!-- Laravel Scaffolding JS -->
<script src="{{ mix('js/laravel.app.js') }}"></script>

<script>
    window.baseUrl = '{{route('admin.home')}}';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('js_after')
</body>
</html>
