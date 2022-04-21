<div class="modal-header">
    <h5 class="modal-title" id="editCategoryModalLabel">Edit Role</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i class="far fa-times-circle text-black"></i>
    </button>
</div>

<form method="POST" action="{{route('roles.update', [$role->id])}}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="edit_role">Role*</label>
                <input type="text" class="form-control" id="edit_role" name="edit_role" value="{{$role->name}}">
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer">
        <input type="hidden" id="role_id" name="role_id" value="{{$role->id}}">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>
