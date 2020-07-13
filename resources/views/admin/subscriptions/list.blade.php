@extends('layouts.admin')

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
            window.page = new Pickitapps.pages.SubscriptionsList();
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Subscriptions</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Subscriptions</li>
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
                <h3 class="block-title">Subscription List</h3>
            </div>
            <div class="block-content block-content-full">
                @can('subscription-create')
                <div class="mb-2">
                    <a class="btn btn-primary" href="{{route('admin.subscriptions.add.show')}}"><i class="si si-plus"></i> Add Subscription</a>
                </div>
                @endcan
                @can('subscription-list')
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                    <tr>
                        <th class="text-center d-none d-sm-table-cell" style="width: 80px;">No</th>
                        <th class="d-none d-sm-table-cell">Name</th>
                        <th class="d-none d-sm-table-cell">Price</th>
                        <th class="d-none d-sm-table-cell">Description</th>
                        <th class="d-none d-sm-table-cell" style="width: 80px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="font-w600">
                                {{$subscription->name}}
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{$subscription->price}}
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{$subscription->description}}
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @can('subscription-edit')
                                    <a href="{{route('admin.subscriptions.edit.show', ['id'=>$subscription->id])}}"
                                       class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    @endcan
                                    @can('subscription-delete')
                                    <a href="javascript:page.delete({{$subscription->id}})" class="btn btn-sm btn-primary"
                                       data-toggle="tooltip" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endcan
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
