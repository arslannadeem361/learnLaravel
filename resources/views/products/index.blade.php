@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Products</h1>
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
                                <h3 class="card-title">All Products</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="all_products" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Regular Price</th>
                                        <th>Discounted Price</th>
                                        <th>Quantity</th>
                                        <th>SKU</th>
                                        <th>Publish Date</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                            @php
                                                if($product->visible == "1"){
                                                    $status = '<span class="badge bg-success">Active</span>';
                                                }else if($product->visible == "0"){
                                                    $status = '<span class="badge bg-warning">Not Active</span>';
                                                }

                                                if(isset($product['productImages'][0])){
                                                    $image = asset('uploads/product_images/'.$product['productImages'][0]['image_path']);
                                                }else{
                                                    $image = asset('uploads/no-image.jpg');
                                                }
                                            @endphp
                                            <tr id="allproducts{{$product->id}}">
                                                <td>{{$loop->iteration}}</td>
                                                <td width="130px">
                                                    <img class="img-thumbnail img-responsive" style="object-fit: cover; width: 80px;height: 80px;"
                                                    src="{{ $image }}">
                                                </td>
                                                <td>{{$product->product_name}}</td>
                                                <td>{{$product->category_name}}</td>
                                                <td>{{$product->regular_price}}</td>
                                                <td>{{$product->discounted_price}}</td>
                                                <td>{{$product->quantity}}</td>
                                                <td>{{$product->sku}}</td>
                                                <td>{{$product->publish_date}}</td>
                                                <td>{!! $status !!}</td>
                                                <td>
                                                    <a href="{{ route('products.edit', [$product->id] )}}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteProduct({{$product->id}});">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Regular Price</th>
                                        <th>Discounted Price</th>
                                        <th>Quantity</th>
                                        <th>SKU</th>
                                        <th>Publish Date</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                    </tfoot>
                                </table>
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
        $(function () {
            $('#all_products').DataTable({
                "order":[0, 'desc'],
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });
        });

        function deleteProduct(product_id)
        {
            bootbox.confirm("Are you sure? Do you really want to delete this product?", function(result)
            {
                if(result)
                {
                    $.ajax({
                        type:"get",
                        url:"{{url('/delete-product')}}/"+product_id,
                        success: function(data)
                        {
                            console.log(data);

                            if ($.trim(data) == "success")
                            {
                                $("#allproducts"+product_id).fadeOut("normal").promise().done(function() {
                                    $(this).remove();
                                });
                            }
                            else
                            {
                                bootbox.dialog({
                                    closeButton: false,
                                    message: "Error while deleting record.",
                                    title: "Error!",
                                    buttons: {
                                        success: {
                                            label: "Close",
                                            className: "btn btn-warning",
                                            callback: function() {
                                                //
                                            }
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
