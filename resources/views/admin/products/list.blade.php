@extends('layouts.client')
@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('js/plugins/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css')}}">
@endsection
@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            window.clientId = {{$client_id}};
            window.page = new Pickitapps.pages.ProductsList();
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Products</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Client</li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Product List</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="mb-2 d-block d-sm-flex justify-content-between">
                    <a class="btn btn-primary mb-2 mb-sm-0"
                       href="{{route('admin.clients.products.add.show', $client_id)}}"><i
                            class="si si-plus"></i> Add Product</a>
                    <div>
                        <a class="btn btn-success"
                           href="{{route('admin.clients.products.toggle-all-active', $client_id)}}"><i
                                class="far fa-eye"></i> Show all</a>
                        <a class="btn btn-warning"
                           href="{{route('admin.clients.products.toggle-all-inactive', $client_id)}}"><i
                                class="far fa-eye-slash"></i> Hide all</a>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">No</th>
                        <th class="d-none d-sm-table-cell" style="width: 100px;">Image</th>
                        <th class="">Product Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 150px;">Category</th>
                        <th class="d-none d-xl-table-cell" style="width: 150px;">Price</th>
                        <th class="d-none d-md-table-cell" style="width: 80px;">Video</th>
                        <th class="text-center" style="width: 80px;">Active</th>
                        <th class="text-center" style="width: 80px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="font-w600 d-none d-sm-table-cell text-center">
                                <img src="{{asset('/media/images/products/thumbnail/').'/'.$product->picture}}"
                                     style="width: 80px;">
                            </td>
                            <td class="">
                                <a href="{{route('admin.clients.products.edit.show', ['client_id' => $client_id, 'id' => $product->id])}}">{{$product->name}}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <a href="{{url('/admin/categories/detail/').'/'.($product->category->id ?? '')}}">{{$product->category->name ?? ''}}</a>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <span
                                    class="badge badge-success">{{$product->price.' '.($product->currency->name ?? '')}}</span>
                            </td>
                            <td class="d-none d-md-table-cell text-center">
                                <a href="javascript:page.openVideoDialog('{{$product->video_id}}', '{{$product->name}}');"><i
                                        class="far fa-play-circle"></i> </a>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch custom-control">
                                    <input type="checkbox" class="custom-control-input"
                                           id="show-toggle-{{$product->id}}" name="show-toggle-{{$product->id}}"
                                           @if($product->active == 1) checked @endif >
                                    <label class="custom-control-label" for="show-toggle-{{$product->id}}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('admin.clients.products.edit.show', ['client_id' => $client_id, 'id' => $product->id])}}"
                                       class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="javascript:page.delete({{$product->id}})" class="btn btn-sm btn-primary"
                                       data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-block-fadein" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Modal Title</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content" style="padding: 8px 8px 0px 8px;">
                        <iframe src="" frameborder="0" allowfullscreen class="iframe-video" width="100%"
                                height="300"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END Page Content -->
@endsection

