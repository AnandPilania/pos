@extends('layouts.app2')

@section('css_before')
    <!-- Page JS Plugins CSS -->
@endsection


@section('content')
    <!-- Hero -->

    <div class="sub-header-products">
        <div class="text-uppercase font-size-h1 text-color-light-1 font-weight-bold">
            Our Clients Shop
        </div>
    </div>
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-3">
                <div class="products-left-top-wrap">
                    <div class="text-color-primary font-weight-bold text-uppercase font-size-h3 margin-bottom-10">
                        Search Products
                    </div>
                    <div class="search-box-wrap">
                        <input autocomplete="off" type="text"  placeholder="Search..." class="product-search-box" />
                        <a href="#" class="search-icon text-color-primary">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="products-left-bottom-wrap">
                    <div class="text-color-primary font-weight-bold text-uppercase font-size-h3 margin-bottom-10">
                        Categories
                    </div>
                    <div>
                        <ul>
                            <li><i class="fa fa-angle-double-right"></i><a href="#">Fitness clothes (45) </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href="#">Fitness Accessories (27) </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href="#">Whey protein (19) </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href="#">Muscle milk (7) </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                <div>
                    @for($i = 0; $i < 6; $i ++)
                        <div class="product-item-one row">
                            <div class="col-md-4 col-sm-12">
                                <div class="product-item-image" style="background-image: url('media/images/products/appview/1570106177.jpg');">
                                </div>
                            </div>
                            <div class="product-item-detail col-md-8 col-sm-12">
                                <div class="text-color-primary font-size-h4 font-weight-bold">Vinyl Kettlebell</div>
                                <div class="text-color-light-3 font-size-h5 margin-bottom-10">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim
                                </div>
                                <div>
                                    <a href="#" class="btn btn-rounded btn-hero-light like-button"><i class="far fa-heart"></i></a>
                                    <a href="#" class="btn btn-rounded btn-hero-light">Restaurant</a>
                                </div>
                            </div>
                        </div>
                    @endfor
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
