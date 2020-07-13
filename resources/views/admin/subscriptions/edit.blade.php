@extends('layouts.admin')
@section('js_after')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <!-- Page JS Code -->
    <script type="text/javascript">
        $(document).ready(function () {
            window.page = new Pickitapps.pages.SubscriptionsAdd();
        });
    </script>
@endsection
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Edit Subscription</h1>
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

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{route('admin.subscriptions.edit', $id)}}" class="js-validation" method="POST">
                    @csrf
                    <h2 class="content-heading">Subscription Info</h2>
                    <div class="row">
                        <div class="col-xl-8 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12 form-group">
                                    <label>
                                        Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name" value="{{$subscription->name}}" placeholder="Client Manager">
                                </div>
                                <div class="col-md-6 col-12 form-group">
                                    <label>
                                        Slug <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                           name="slug" value="{{$subscription->slug}}"
                                           placeholder="client-manager">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    Price <span class="text-danger">*</span> (Monthly)
                                </label>
                                <input type="text" class="form-control @error('price') is-invalid @enderror"
                                       name="price" value="{{$subscription->price}}" placeholder="Price">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" rows="5"
                                          placeholder="...">{{$subscription->description}}</textarea>
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
                                @foreach($sanctions as $sanction)
                                    <tr>
                                        <td class="font-w600">
                                            <div class="custom-control custom-checkbox custom-control-primary">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="sanction-{{$sanction->id}}" name="sanctions[]"
                                                       value="{{$sanction->id}}"
                                                       @foreach($subscription->sanctions as $p)
                                                       @if($p->id == $sanction->id)
                                                       checked
                                                    @break
                                                    @endif
                                                    @endforeach
                                                >
                                                <label class="custom-control-label"
                                                       for="sanction-{{$sanction->id}}">{{$sanction->name}}</label>
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">{{$sanction->slug}}</td>
                                        <td class="d-none d-lg-table-cell">{{$sanction->description}}</td>
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
                                <a class="btn btn-danger" href="{{route('admin.subscriptions.show')}}">
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
