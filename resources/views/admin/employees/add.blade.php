@extends('layouts.admin')

@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <!-- Page JS Code -->
    <script type="text/javascript">
        $(document).ready(function () {
            window.page = new Pickitapps.pages.EmployeesAdd();
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Add Employee</h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded block-bordered">
            <div class="block-content">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{route('admin.employees.add')}}" class="js-validation" method="POST">
                    @csrf
                    <h2 class="content-heading">Personal Information</h2>
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>
                                        First Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('first-name') is-invalid @enderror"
                                           name="first-name" placeholder="First Name">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>
                                        Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('last-name') is-invalid @enderror"
                                           name="last-name" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" placeholder="Password">
                            </div>
                        </div>
                    </div>

                    <h2 class="content-heading">Positions <span class="text-danger">*</span></h2>
                    <div class="row">
                        <div class="col-xl-8 col-12">
                            <table class="table table-sm table-vcenter">
                                <thead>
                                <tr>
                                    <th class="pl-4">Name</th>
                                    <th class="d-none d-sm-table-cell">Slug</th>
                                    <th class="d-none d-lg-table-cell">Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td class="font-w600">
                                            <div class="custom-control custom-checkbox custom-control-primary">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="permission-{{$position->id}}" name="positions[]"
                                                       value="{{$position->id}}">
                                                <label class="custom-control-label"
                                                       for="permission-{{$position->id}}">{{$position->name}}</label>
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">{{$position->slug}}</td>
                                        <td class="d-none d-lg-table-cell">{{$position->description}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Submit -->
                    <div class="row push">
                        <div class="col-lg-8 col-xl-5">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-check-circle mr-1"></i> Submit
                                </button>
                                <a class="btn btn-danger" href="{{route('admin.employees.show')}}">
                                    <i class="fa fa-times-circle mr-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END Submit -->
                </form>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
