<!-- Add Category Modal-->
<div class="modal fade" id="CategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> {{-- Category Modal Title --}} </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {{-- Category Form --}}
            <form id="CategoryModalForm" method="post">
                @csrf
                <div class="modal-body">

                    <input type="hidden" id="edit_category_id" name="edit_category_id" class="form-control">

                    {{-- Category Name --}}
                    <div class="form-group">
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Category Name" value="">
                    </div>

                    {{-- Save And Cancel Button --}}
                    <div class="modal-footer">
                        <button type="submit" onclick="saveCategory()" id="CategoryModalSubmit"
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
