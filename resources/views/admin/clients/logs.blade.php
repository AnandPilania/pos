@extends('layouts.client')
@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css')}}">
@endsection
@section('js_after')
    <!-- Page JS Plugins -->
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
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Logs</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Client</li>
                        <li class="breadcrumb-item active" aria-current="page">Logs</li>
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
                <h3 class="block-title">Log List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                    <tr>
                        <th class="d-none d-sm-table-cell text-center" style="width: 80px;">No</th>
                        <th class="d-none d-sm-table-cell">Action</th>
                        <th class="d-none d-sm-table-cell">PerformedId</th>
                        <th class="d-none d-sm-table-cell">PerformedOn</th>
                        <th class="d-none d-sm-table-cell">CausedId</th>
                        <th class="d-none d-sm-table-cell">CausedOn</th>
                        <th class="d-none d-xl-table-cell">Detail</th>
                        <th class="d-none d-xl-table-cell">DateTime</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center">{{$log->description}}</td>
                            <td class="text-center">{{$log->subject_id}}</td>
                            <td class="text-center">{{$log->subject_type}}</td>
                            <td class="text-center">{{$log->causer_id}}</td>
                            <td class="text-center">{{$log->causer_type}}</td>
                            <td class="text-center">{{$log->properties}}</td>
                            <td class="text-center">{{$log->updated_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
