@extends('layouts.admin')

@section('content')
    <!-- Hero -->
    <div class="bg-image" style="background-image: url({{asset('media/photos/Food4.jpg')}});">
        <div class="bg-white-90">
            <div class="content content-full">
                <div class="row">
                    <div class="col-md-6 d-md-flex align-items-md-center">
                        <div class="py-4 py-md-0 text-center text-md-left invisible" data-toggle="appear">
                            <h1 class="font-size-h2 mb-2">Dashboard</h1>
                            <h2 class="font-size-lg font-w400 text-muted mb-0">Today is a great one!</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="row gutters-tiny push">
            <div class="col-6 col-md-4 col-xl-3">
                <a class="block text-center bg-image" style="background-image: url({{asset('media/photos/photo19.jpg')}});" href="{{url('/admin/employees')}}">
                    <div class="block-content block-content-full bg-primary-op aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                        <div>
                            <div class="font-size-h1 font-w300 text-white">{{$employees}}</div>
                            <div class="font-w600 mt-3 text-uppercase text-white">EmployeesList</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <a class="block text-center bg-image" style="background-image: url({{asset('media/photos/photo18.jpg')}});" href="{{url('/admin/customers')}}">
                    <div class="block-content block-content-full bg-xsmooth-op aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                        <div>
                            <div class="font-size-h1 font-w300 text-white">{{$customers}}</div>
                            <div class="font-w600 mt-3 text-uppercase text-white">Customers</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <a class="block text-center bg-image" style="background-image: url({{asset('media/photos/Food4.jpg')}});" href="{{url('/admin/products')}}">
                    <div class="block-content block-content-full bg-xmodern-op aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                        <div>
                            <div class="font-size-h1 font-w300 text-white">{{$products}}</div>
                            <div class="font-w600 mt-3 text-uppercase text-white">Products</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <a class="block text-center bg-image" style="background-image: url({{asset('media/photos/Food5.jpg')}});" href="{{url('/admin/categories')}}">
                    <div class="block-content block-content-full bg-gd-sublime-op aspect-ratio-16-9 d-flex justify-content-center align-items-center">
                        <div>
                            <div class="font-size-h1 font-w300 text-white">{{$categories}}</div>
                            <div class="font-w600 mt-3 text-uppercase text-white">Categories</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
