@extends('layouts.admin')
@section('js_after')
    <script src="{{asset('js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <!-- Page JS Code -->
    <script type="text/javascript">
        $(document).ready(function () {
            window.page = new Pickitapps.pages.CategoriesAdd();
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-image" style="background-image: url({{asset('media/photos/Food3.jpg')}});">
        <div class="bg-black-50">
            <div class="content content-full">
                <h1 class="font-size-h2 text-white my-2">
                    <i class="fa fa-pencil-alt text-white-50 mr-1"></i> Edit Category
                </h1>
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

                @if ($message = Session::get('warning'))
                    <div class="alert alert-warning alert-block">
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

                <form action="{{route('admin.clients.categories.edit', ['client_id' => $client_id, 'id' => $category->id])}}"
                      method="POST" class="ja-validation">
                @csrf
                <!-- Vital Info -->
                    <h2 class="content-heading pt-0">Category Information</h2>
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="text-muted">
                                Some vital information about category
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <div class="form-group">
                                <label for="dm-project-new-name">
                                    Category Name (English) <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="category-name" placeholder="eg: Pizza"
                                       value="{{$category->name}}">
                            </div>
                            <div class="form-group">
                                <label>
                                    Category Name (Other language)
                                </label>
                                <div
                                    class="custom-control custom-checkbox custom-control-inline custom-control-primary mb-1">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-name-rtl"
                                           name="rtl-direction" @if($category->rtl_direction == 1) checked @endif>
                                    <label class="custom-control-label" for="checkbox-name-rtl">RTL?</label>
                                </div>
                                <input type="text" class="form-control" name="category-name-ar" placeholder="eg: Pizza"
                                       value="{{$category->name_second}}"
                                       @if($category->rtl_direction == 1) dir="rtl" @endif>
                            </div>
                            <div class="form-group">
                                <label for="dm-project-new-name">
                                    Display Order
                                </label>
                                <input type="text" class="form-control" name="order" placeholder="Order Number"
                                       value="{{$category->show_order}}">
                            </div>
                        </div>
                    </div>
                    <!-- END Vital Info -->
                    <input type="hidden" value="{{$category->id}}" name="id">
                    <!-- Submit -->
                    <div class="row push">
                        <div class="col-lg-8 col-xl-5 offset-lg-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check-circle mr-1"></i> Update Category
                                </button>
                                <a class="btn btn-warning"
                                   href="{{route('admin.clients.categories.show', $client_id)}}">
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

