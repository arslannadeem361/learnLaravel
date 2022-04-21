@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <style>
        .img-thumb {
            height: 65px;
            width: 65px;
            object-fit: cover;
            border: 2px solid #000;
            border-radius:3px;
            padding: 1px;
            cursor: pointer;
        }
        .img-thumb-wrapper {
            display: inline-block;
            margin: 10px 10px 0 0;
        }
        .remove {
            display: block;
            background: #444;
            border: 1px solid #000;
            color: white;
            text-align: center;
            cursor: pointer;
        }
        .remove:hover {
            background: white;
            color: black;
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create Product</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Add New Product</h3>
                            </div>

                            @include('layouts.partials.flash-messages')

                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="saveProduct" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">

                                        @if ($errors->any())
                                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Product Name</label>
                                                    <input type="text" class="form-control" name="product_name" placeholder="Enter ...">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Regular Price</label>
                                                    <input type="number" name="regular_price" class="form-control" placeholder="Enter ..."
                                                    onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)'>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Dicounted Price</label>
                                                    <input type="number" name="discounted_price" class="form-control" placeholder="Enter ..."
                                                    onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)'>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Quantity</label>
                                                    <input type="number" name="quantity" class="form-control" placeholder="Enter ..."
                                                    onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)'>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Select Category</label>
                                                    <select class="form-control select2bs4" id="category_id" name="category_id">
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Sub Category</label>
                                                    <select class="form-control select2bs4" id="sub_category_id" name="sub_category_id">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>SKU</label>
                                                    <input type="text" name="sku" class="form-control" placeholder="Enter ...">
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" id="visible" name="visible">
                                                        <option value="1">Active</option>
                                                        <option value="0">Not Active</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Publish Date</label>
                                                    <div class="input-group date" >
                                                        <input type="date" class="form-control" name="publish_date" value="{{date('Y-m-d')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="image">Upload Image</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" multiple class="custom-file-input" id="image" name="image[]" accept="image/png, image/jpg, image/JPG, image/jpeg"
                                                            data-show-preview="false">
                                                            <label class="custom-file-label" for="image">Choose file</label>
                                                        </div>
                                                    </div>
                                                    <div id="galleria">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Short Description</label> <br>
                                                    <textarea id="short_description" name="short_description" rows="5"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Long Description</label> <br>
                                                    <textarea id="long_description" name="long_description" rows="5"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            if (window.File && window.FileList && window.FileReader) {
                $("#image").on("change", function(e) {
                    var files = e.target.files,
                        filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target;
                            $("<div class=\"img-thumb-wrapper card shadow\">" +
                                "<img class=\"img-thumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                                "<br/><span class=\"remove\">Remove</span>" +
                                "</div>").insertAfter("#galleria");
                            $(".remove").click(function(){
                                $(this).parent(".img-thumb-wrapper").remove();
                            });

                        });
                        fileReader.readAsDataURL(f);
                    }
                    console.log(files);
                });
            } else {
                alert("Your browser doesn't support to File API")
            }
        });

        $('#short_description').summernote({
            placeholder: 'Please add product short description here.',
            tabsize: 2,
            height: 100
        });

        $('#long_description').summernote({
            placeholder: 'Please add product long description here.',
            tabsize: 2,
            height: 100
        });

        $('select#category_id').change(function()
        {
            $(this).find("option:selected").each(function()
            {
                var selected_option = $(this).attr("value");

                if(selected_option){
                    $.ajax({
                        type:"get",
                        url:"{{url('/get-sub-categories')}}/"+selected_option,
                        success:function(response)
                        {
                            if(response)
                            {
                                $('#sub_category_id').empty();
                                $('#sub_category_id').append('<option value="">Select Sub Category</option>');
                                $.each(response,function(key,value){
                                    $('#sub_category_id').append('<option value="'+key+'">'+value+'</option>');
                                });
                            }
                        }
                    });
                }
            });
        }).change();

        $(function () {
            // $.validator.setDefaults({
            //     submitHandler: function () {
            //         alert( "Form successful submitted!" );
            //     }
            // });
            $('#saveProduct').validate({
                rules: {
                    product_name: {
                        required: true,
                    },
                    regular_price: {
                        required: true,
                    },
                    category_id: {
                        required: true,
                    },
                    image: {
                        required: true,
                    },
                },
                messages: {
                    product_name: {
                        required: "Please enter product name",
                    },
                    regular_price: {
                        required: "Please enter regular price",
                    },
                    category_id: {
                        required: "Please select category",
                    },
                    image: {
                        required: "Please upload image",
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
