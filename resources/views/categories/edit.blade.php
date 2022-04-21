<div class="modal-header">
    <h5 class="modal-title" id="editCategoryModalLabel">Create New Category</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i class="far fa-times-circle text-black"></i>
    </button>
</div>
<form id="updateCategory">
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="edit_category_name">Category Name*</label>
                <input type="text" class="form-control" id="edit_category_name" name="edit_category_name" value="{{$category->category_name}}">
            </div>

            <div class="form-group">
                <label>Parent Category*</label>
                <select class="form-control" id="edit_parent_id" name="edit_parent_id">
                    @if(isset($option_category->id))
                        <option value="{{$option_category->id}}">{{$option_category->category_name}}</option>
                    @endif

                    @foreach($all_categories as $category)
                        <option value="{{$category->id}}">
                            {{$category->category_name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="edit_category_visible" {{ $category->visible == 1 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="edit_category_visible">Visible</label>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer">
        <input type="hidden" id="category_id" name="category_id" value="{{$category->id}}">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateCategory({{$category->id}})">Save changes</button>
    </div>
</form>
