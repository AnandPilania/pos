@extends('layouts.admin')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Add Position</h1>
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

                <form action="{{route('admin.positions.add')}}" method="POST">
                    @csrf
                    <h2 class="content-heading">Position Info</h2>
                    <div class="row">
                        <div class="col-xl-8 col-12">
                            <div class="form-group row">
                                <div class="col-md-6 col-12">
                                    <label>
                                        Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Client Manager">
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>
                                            Slug <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                                               placeholder="client-manager">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" rows="3"
                                          placeholder="Client Manager Role has the whole permissions to manage clients, like add/edit/delete clients and etc ..."></textarea>
                            </div>
                        </div>
                    </div>

                    <h2 class="content-heading">Permissions <span class="text-danger">*</span></h2>
                    <div class="row">
                        <div class="col-xl-8 col-12">
                            <table class="table table-sm table-borderless table-vcenter">
                                <thead>
                                <tr>
                                    <th class="pl-4">Name</th>
                                    <th class="d-none d-sm-table-cell">Slug</th>
                                    <th class="d-none d-lg-table-cell">Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td class="font-w600">
                                            <div class="custom-control custom-checkbox custom-control-primary">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="permission-{{$permission->id}}" name="permissions[]"
                                                       value="{{$permission->id}}">
                                                <label class="custom-control-label"
                                                       for="permission-{{$permission->id}}">{{$permission->name}}</label>
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">{{$permission->slug}}</td>
                                        <td class="d-none d-lg-table-cell">{{$permission->description}}</td>
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
                                <a class="btn btn-danger" href="{{route('admin.positions.show')}}">
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

@section('js_after')

    <!-- Page JS Code -->
    <script>

    </script>
@endsection
