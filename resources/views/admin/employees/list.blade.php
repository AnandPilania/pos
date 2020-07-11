@extends('layouts.admin')

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Employees</h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Admin</li>
                        <li class="breadcrumb-item active" aria-current="page">Employees</li>
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
                <h3 class="block-title">Employee List</h3>
            </div>
            <div class="block-content block-content-full">
                <div style="margin-bottom: 10px;">
                    <a class="btn btn-primary" href="{{route('admin.employees.add.show')}}"><i class="fa fa-user-plus"></i> Add Employee</a>
                </div>
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                    <thead>
                    <tr>
                        <th class="text-center d-none d-sm-table-cell" style="width: 80px;">No</th>
                        <th class="d-none d-sm-table-cell">Name</th>
                        <th class="d-none d-sm-table-cell">Email</th>
                        <th class="d-none d-sm-table-cell" style="width: 80px;">Enable</th>
                        <th class="d-none d-sm-table-cell" style="width: 80px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="font-w600">
                                {{$employee->first_name.' '.$employee->last_name}}
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{$employee->email}}
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch custom-control custom-control-inline mb-2"
                                     align="center">
                                    <input type="checkbox" class="custom-control-input"
                                           id="enable-toggle-{{$employee->id}}" name="enable-toggle-{{$employee->id}}"
                                           @if($employee->active == 1) checked @endif >
                                    <label class="custom-control-label" for="enable-toggle-{{$employee->id}}"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('admin.employees.edit.show', ['id'=>$employee->id])}}"
                                       class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="javascript:delEmployee({{$employee->id}})" class="btn btn-sm btn-primary"
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

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('js/pages/be_tables_datatables.min.js')}}"></script>
    <script>
        function delEmployee(id) {
            if (confirm("Do you want delete this employee's account?")) {
                $.ajax({
                    url: '{{url('/admin/employees/del')}}',
                    type: "POST",
                    data: {
                        "id": id,
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.message.length == 0) {
                            window.location.reload();
                        }
                    }
                });
            }
        }

        $(document).ready(function () {
            $("[name^='enable-toggle-']").on('change', function () {
                var id = this.name.split("enable-toggle-")[1];
                $.ajax({
                    url: '{{url('/admin/employees/toggle-enable')}}',
                    type: "POST",
                    data: {
                        "id": id,
                    },
                    error: function () {
                    },
                    success: function (data) {
                        if (data.message.length == 0) {
                            //window.location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endsection
