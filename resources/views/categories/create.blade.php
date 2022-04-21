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
                        <h1 class="m-0">Create Category</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success btn-md float-lg-right" onclick="createCategory()">
                            <i class="fa fa-plus-circle"> </i> Create Category
                        </button>
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
                                <h3 class="card-title">All Categories</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="all_categories" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Category Name</th>
                                            <th>Parent</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $category)
                                            @php
                                                if($category->visible == "1"){
                                                    $status = '<span class="badge bg-success">Active</span>';
                                                }else if($category->visible == "0"){
                                                    $status = '<span class="badge bg-warning">Not Active</span>';
                                                }
                                            @endphp
                                            <tr id="categrow{!! $category->id !!}">
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$category->category_name}}</td>
                                                <td>
                                                    {{\App\Categories::where('id', $category->parent_id)->value('category_name')}}
                                                </td>
                                                <td>{!! $status !!}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" onclick="editCategoryModal({{$category->id}});">
                                                       <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteCategory({{$category->id}})">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Category Name</th>
                                            <th>Parent</th>
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

    <!-- Create Category Modal --->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="far fa-times-circle text-black"></i>
                    </button>
                </div>
                <form id="saveCategory">
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category_name">Category Name*</label>
                                <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter category name">
                            </div>

                            <div class="form-group">
                                <label>Parent Category*</label>
                                <select class="form-control" id="edit_parent_category_id" name="edit_parent_category_id">
                                    <option value="0">--None--</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">
                                            {{$category->category_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="category_visible">
                                    <label class="custom-control-label" for="category_visible">Visible</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveCategory()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal --->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="load_edit_category_modal">

            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function () {
            $('#all_categories').DataTable({
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

        function editCategoryModal(catId)
        {
            $("#load_edit_category_modal").html('<h4 class="text-center text-primary mt50 "><i class="fa fa-spinner fa-spin mb50"></i> Please wait loading....</h4>');
            $.ajax({
                type: "GET",
                url: "{{url('edit-category')}}/"+catId,
                cache:false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#load_edit_category_modal").html(data);
                }
            });

            $('#editCategoryModal').modal();
        }

        function createCategory()
        {
            $('#createCategoryModal').modal('show');
        }

        function saveCategory()
        {
            var isError = 0;
            $('#category_name').on('keyup',function(){
                if($(this).val().length > 0){
                    $('#category_name').removeClass('is-invalid');
                }
            });

            var category_name = $('#category_name').val();
            var parent_id = $('#parent_id').val();

            var categoryVisibleToggle = $('#category_visible').is(':checked');
            if (categoryVisibleToggle == true)
            {
                var category_visible = 1;
            }else if (categoryVisibleToggle == false){
                var category_visible = 0;
            }

            if(category_name == ""){
                isError = 1;
                $("#category_name").addClass("is-invalid");
                //  $( "<p class='text-danger' '>Please enter category name</p>" ).insertAfter( "#category_name" );
            }

            if(isError == 0)
            {
                $.ajax({
                    type: "POST",
                    url: "{{route('categories.store')}}",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        category_name:category_name,
                        parent_id:parent_id,
                        category_visible:category_visible
                    },
                    success: function(data)
                    {
                        if($.trim(data) == "success"){
                            bootbox.dialog({
                                closeButton: false,
                                message: category_name+" saved successfully.",
                                title: "Success!",
                                buttons: {
                                    success: {
                                        label: "Close",
                                        className: "btn btn-success",
                                        callback: function() {
                                            location.reload();
                                        }
                                    }
                                }
                            });
                        }else{
                            toastr.error("Error while saving record.");

                            $('#createCategoryModal').modal('hide');
                        }
                    }
                })
            }
        }

        function updateCategory(catId)
        {
            var isError = 0;
            $('#edit_category_name').on('keyup',function(){
                if($(this).val().length > 0){
                    $('#edit_category_name').removeClass('is-invalid');
                }
            });

            var edit_category_name = $('#edit_category_name').val();
            var edit_parent_category_id = $('#edit_parent_category_id').val();

            var categoryVisibleToggleEdit = $('#edit_category_visible').is(':checked');
            if (categoryVisibleToggleEdit == true)
            {
                var edit_category_visible = 1;
            }else if (categoryVisibleToggleEdit == false){
                var edit_category_visible = 0;
            }

            if(edit_category_name == ""){
                isError = 1;
                $("#edit_category_name").addClass("is-invalid");
                //  $( "<p class='text-danger' '>Please enter category name</p>" ).insertAfter( "#category_name" );
            }

            if(isError == 0)
            {
                $.ajax({
                    type: "post",
                    url: "{{route('category_update')}}"+"/"+catId,
                    data:{
                        catId:catId,
                        edit_category_name:edit_category_name,
                        edit_parent_category_id:edit_parent_category_id,
                        edit_category_visible:edit_category_visible
                    },
                    success: function(data)
                    {
                        console.log(data);

                        if($.trim(data) == "success"){
                            bootbox.dialog({
                                closeButton: false,
                                message: edit_category_name+" saved successfully.",
                                title: "Success!",
                                buttons: {
                                    success: {
                                        label: "Close",
                                        className: "btn btn-success",
                                        callback: function() {
                                            location.reload();
                                        }
                                    }
                                }
                            });
                        }else{
                            toastr.error("Error while saving record.");

                            $('#editCategoryModal').modal('hide');
                        }
                    }
                })
            }
        }

        function deleteCategory(catId)
        {
            //alert(catId);
            bootbox.confirm("Are you sure? Do you really want to delete this category?", function(result)
            {
                if(result)
                {
                    $.ajax({
                        type: "POST",
                        url: "{{route('category_delete')}}",
                        data: {catId:catId, "_token": "{{ csrf_token() }}"},
                        success: function(data)
                        {
                            console.log(data);

                            if ($.trim(data) == "success")
                            {
                                $("#categrow"+catId).fadeOut("normal").promise().done(function() {
                                    $(this).remove();
                                });
                                //location.reload();
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
