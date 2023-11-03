<!-- Add Category Modal-->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {{-- Add Category Form --}}
            <form id="addCategoryModalForm" method="post">
                @csrf
                <div class="modal-body">

                    {{-- Category Name --}}
                    <div class="form-group">
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Category Name" value="">
                    </div>

                    {{-- Save And Cancel Button --}}
                    <div class="modal-footer">
                        <button type="submit" onclick="addCategory()" id="CategoryModalSubmit"
                            class="btn btn-primary ">
                            Save
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal-->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- Edit Category Form --}}
            <form id="editCategoryModalForm" method="post">
                @csrf
                <div class="modal-body">

                    {{-- Category Name --}}
                    <div class="form-group">
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Category Name" value="">
                    </div>
                    <input type="hidden" id="edit_category_id" name="edit_category_id" class="form-control">
                </div>

                {{-- Save And Cancel Button --}}
                <div class="modal-footer">
                    <button type="submit" onclick="editCategory()" class="btn btn-primary ">Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
