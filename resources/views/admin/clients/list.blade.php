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
            window.page = new Pickitapps.pages.ClientsList();
        });
    </script>
@endsection
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Clients</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Clients</li>
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
                <h3 class="block-title">Client List</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="mb-2">
                    <a class="btn btn-primary" href="{{route('admin.clients.add.show')}}">
                        <i class="si si-user-follow"></i> Add Client</a>
                </div>

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                    <tr>
                        <th class="d-none d-sm-table-cell text-center" style="width: 80px;">No</th>
                        <th class="d-none d-sm-table-cell">Name</th>
                        <th class="d-none d-sm-table-cell">Company</th>
                        <th class="d-none d-xl-table-cell">StartDate</th>
                        <th class="d-none d-xl-table-cell">ExpireDate</th>
                        <th class="d-none d-sm-table-cell" style="width: 80px;">Enable</th>
                        <th class="d-none d-sm-table-cell" style="width: 80px;">Print</th>
                        <th class="d-none d-sm-table-cell" style="width: 80px;">Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="font-w600">
                                <a href="{{route('admin.clients.detail.show', ['client_id' => $client->id])}}">
                                    {{$client->first_name.' '.$client->last_name}}
                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{ $client->company }}
                            </td>
                            <td class="d-none d-xl-table-cell">
                                {{ date('d M Y', strtotime($client->start_date)) }}
                            </td>
                            <td class="d-none d-xl-table-cell">
                                {{ date('d M Y', strtotime($client->expire_date)) }}
                            </td>

                            <td class="text-center">
                                <div class="custom-control custom-switch custom-control"
                                     align="center">
                                    <input type="checkbox" class="custom-control-input"
                                           id="enable-toggle-{{$client->id}}"
                                           name="enable-toggle-{{$client->id}}"
                                           @if($client->active == 1) checked @endif >
                                    <label class="custom-control-label"
                                           for="enable-toggle-{{$client->id}}"></label>
                                </div>
                            </td>

                            <td class="text-center">
                                <a href="{{route('admin.clients.invoice.preview', $client->id)}}"><i
                                        class="si si-printer"></i></a>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('admin.clients.edit.show', ['id'=>$client->id])}}"
                                       class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="javascript:page.delete({{$client->id}})"
                                       class="btn btn-sm btn-primary"
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
