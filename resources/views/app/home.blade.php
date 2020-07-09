@extends('layouts.app1')

@section('css_before')
    <!-- Page JS Plugins CSS -->
@endsection


@section('content')
    <!-- Hero -->

    <div class="content">

        <div class="row gap-y align-items-center margin-top-80">
            <div class="col-lg-5 mx-auto text-center ">
                <h1 class="text-uppercase font-size-h1" style="color: #32c1d6; font-size: 4em;">Pick it</h1>
                <p class="color-1 lead alpha-8 my-5 text-color-light-1">“Customizing your own business”</p>
                <a href="https://my.pickitapps.com" class="btn btn-block btn-hero-info home-joinus-button" >Join Us</a>
            </div>
            <div class="col-lg-6 col-md-9 mx-md-auto mx-lg-0 pr-lg-0">
                <div class="device-twin align-items-center mt-4 mt-lg-0">
                    <div class="browser shadow home-image-1-wrap" data-aos="fade-left">
                        <img src="{{asset('media/backgrounds/home_2.png')}}" alt="..." class="home-image-1">
                    </div>
                    <div class="front absolute d-none d-lg-block mobile-home-image-2" data-aos="fade-right" style="left: -5.5rem; bottom: -3.5rem;">
                        <div class="screen">
                            <img src="{{asset('media/backgrounds/home_1.png')}}" class="home-image-2" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gap-y align-items-center py-5 home-section-item">
            <div class="col-md-6">
                <figure data-aos="fade-right">
                    <img src="{{asset('media/backgrounds/home_3.png')}}" class="img-responsive for-mobile-img4" alt="">
                </figure>
            </div>
            <div class="col-md-6">
                <h2 class="home-paragraph-header">PICK IT System</h2>
                <p class="home-paragraph-content"> What is pick it? It is an application that will change your business to a whole new level. Now with pick it system you can customize your work or menu as you need, there will be no mistakes, no worries, all you will get is an organized work. You can now with pick it customize your work as you can, like no one can change anything in your menu or services, only you can change that and control that, changing colors and the whole layout, and if there is anything in your menu or services that you don’t want to show you can hide it “controlling the hide & show “. Now with pick it system your business will be as easy as you want, we made this application to make your business more delightful.  </p>
            </div>
        </div>

        <div class="row gap-y align-items-center py-5 home-section-item">
            <div class="col-md-8">
                <figure data-aos="fade-right">
                    <img src="{{asset('media/backgrounds/home_4.png')}}" class="img-responsive for-mobile-img4 home-image-4" alt="">
                </figure>
            </div>
            <div class="col-md-4">
                <h2 class="home-paragraph-header">Our Powerful Tools</h2>
                <ul class="home-paragraph-content-ul">
                    <li><i class="fa fa-check"></i><span>Customizing your Menu, services as you need.</span></li>
                    <li><i class="fa fa-check"></i><span>Changing the whole layout, font, colors and even the background.</span></li>
                    <li><i class="fa fa-check"></i><span>No one can change or do anything in your services or menu “Except you”.</span></li>
                    <li><i class="fa fa-check"></i><span>The “Hide & show “bottom which will helps you show or hide anything you want from your menu or services.</span></li>
                    <li><i class="fa fa-check"></i><span>There will be a link to put it anywhere you need for your customers to see your work.</span></li>
                </ul>
            </div>
        </div>

    </div>

    <!-- END Page Content -->
@endsection

@section('js_after')

    <!-- Page JS Code -->

    <!-- Page JS Helpers (Select2 plugin) -->
    <script>jQuery(function(){ Dashmix.helpers('select2'); });</script>

@endsection
