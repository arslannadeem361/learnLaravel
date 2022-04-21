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
                        <h1 class="m-0">Roles</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        @include('layouts.partials.flash-messages')

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">All Roles</h3>
                                <button type="button" class="btn btn-info btn-md float-lg-right" data-toggle="modal" data-target="#createRoleModal">
                                    <i class="fa fa-plus"> Create Role</i>
                                </button>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="all_roles" class="table table-bordered table-striped table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Guard</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $key => $role)
                                            <tr id="roles_row{{$role->id}}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>{{ $role->guard_name }}</td>
                                                <td align="center">
                                                    <button type="button" class="btn btn-sm btn-success" onclick="editRoleModal({{$role->id}});">Edit</button>
                                                </td>
                                                <td align="center">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteRole({{$role->id}});">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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


    {{-- Create Role Modal **-start-** --}}
    <div class="modal fade show" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="far fa-times-circle text-black"></i>
                    </button>
                </div>
                {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
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
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Edit Role Modal **-start-** --->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="load_edit_role_modal">
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $(function () {
            $('#all_roles').DataTable({
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

        function editRoleModal(Id)
        {
            $("#load_edit_role_modal").html('<h4 class="text-center text-primary mt50 "><i class="fa fa-spinner fa-spin mb50"></i> Please wait loading....</h4>');
            $.ajax({
                type: "GET",
                url: "{{url('edit-role')}}/"+Id,
                cache:false,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#load_edit_role_modal").html(data);
                }
            });

            $('#editRoleModal').modal();
        }

        function deleteRole(roleId)
        {
            //alert(catId);
            bootbox.confirm("Are you sure? Do you really want to delete this category?", function(result)
            {
                if(result)
                {
                    $.ajax({
                        type: "POST",
                        url: "{{route('role_delete')}}",
                        data: {roleId:roleId, "_token": "{{ csrf_token() }}"},
                        success: function(data)
                        {
                            console.log(data);

                            if ($.trim(data) == "success")
                            {
                                $("#roles_row"+roleId).fadeOut("normal").promise().done(function() {
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
