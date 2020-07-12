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
            window.page = new Pickitapps.pages.CategoriesList();
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Categories</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Client</li>
                        <li class="breadcrumb-item active" aria-current="page">Categories</li>
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
                <h3 class="block-title">Category List</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="d-block d-sm-flex justify-content-between mb-2">
                    <a class="btn btn-primary mb-2" href="{{route('admin.clients.categories.add.show', $client_id)}}"><i
                            class="si si-plus"></i> Add Category</a>
                    <div>
                        <a class="btn btn-success"
                           href="{{route('admin.clients.categories.toggle-all-active', $client_id)}}"><i
                                class="far fa-eye"></i> Show all</a>
                        <a class="btn btn-warning"
                           href="{{route('admin.clients.categories.toggle-all-inactive', $client_id)}}"><i
                                class="far fa-eye-slash"></i> Hide all</a>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">#</th>
                        <th class="">Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 120px;">Order</th>
                        <th class="" style="width: 120px;">Show</th>
                        <th class="" style="width: 120px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="">
                                <a href="{{route('admin.clients.categories.edit.show', ['client_id' => $client_id, 'id' => $category->id])}}">{{$category->name}}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{$category->show_order}}
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch custom-control "
                                     align="center">
                                    <input type="checkbox" class="custom-control-input"
                                           id="show-toggle-{{$category->id}}" name="show-toggle-{{$category->id}}"
                                           @if($category->active == 1) checked @endif >
                                    <label class="custom-control-label" for="show-toggle-{{$category->id}}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('admin.clients.categories.edit.show', ['client_id' => $client_id, 'id' => $category->id])}}"
                                       class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="javascript:page.delete({{$category->id}})" class="btn btn-sm btn-primary"
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
    <!-- END Page Content -->
@endsection
