<?php $__env->startSection('title', 'Exp. Mgr. | Category'); ?>


<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Category</h1>
            
            <a class="btn btn-primary btn-icon-split d-none d-sm-inline-block shadow-sm" id="openCategoryModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Category</span>
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Of Category</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-bordered" id="categoryListTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

<?php $__env->stopSection(); ?>



<?php $__env->startSection('jsContent'); ?>

    <script>
        $(document).ready(function() {

            // make yajra Table
            $('#categoryListTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('category.list')); ?>",
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ]
            });

            // Reset Category Modal Form
            $(document).on("click", "#openCategoryModal", function(event) {
                $("#CategoryModalForm #name").val('');
                $("#CategoryModalForm #edit_category_id").val('');
                $('#CategoryModal .modal-title').text('Add Category');
                $('#CategoryModal').modal('show');
            });
        });

        // Function For Add & Edit Category Using Same Modal Besed on Condition
        function saveCategory() {
            let edit_category_id = $("#CategoryModalForm #edit_category_id").val();

            $("#CategoryModalForm").validate({
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Please Specify your Category name",
                },
                submitHandler: function(form) {
                    if (edit_category_id == "") {
                        $.ajax({
                            type: 'post',
                            data: $('#CategoryModalForm').serialize(),
                            url: "<?php echo e(route('category.save')); ?>",
                            success: function(response) {
                                if (response.status == "success") {
                                    toastr.success('' + response.message + '');
                                } else {
                                    toastr.error('' + response.message + '');
                                }

                                var categoryListTable = $('#categoryListTable').dataTable();
                                categoryListTable.fnDraw(false);

                                $('#CategoryModal').modal('hide');

                            }

                        });
                    } else {
                        $.ajax({
                            type: 'post',
                            data: $('#CategoryModalForm').serialize(),
                            url: "<?php echo e(route('category.edit')); ?>",
                            success: function(response) {
                                if (response.status == "success") {
                                    toastr.success('' + response.message + '');
                                } else {
                                    toastr.error('' + response.message + '');
                                }

                                var categoryListTable = $('#categoryListTable').dataTable();
                                categoryListTable.fnDraw(false);

                                $('#CategoryModal').modal('hide');
                            }
                        });
                    }
                }
            });
        }

        // View Category Ajax call First Validate After That Put Data Into Modal
        function viewCategory(id = "") {
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "<?php echo e(route('category.view')); ?>",
                success: function(response) {
                    $('#CategoryModal .modal-title').text('Edit Category');
                    $('#CategoryModalForm #name').val(response.name);
                    $('#CategoryModalForm #edit_category_id').val(response.id);
                    $('#CategoryModal').modal('show');
                }
            })
        }

        // Delete Category Ajax call First Validate After That Submit In Response Show Toast Message And ReDraw Datatable
        function deleteCategory(id = "") {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id: id,
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        url: "<?php echo e(route('category.delete')); ?>",
                        success: function(response) {
                            if (response.status == "success") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }

                            var categoryListTable = $('#categoryListTable').dataTable();
                            categoryListTable.fnDraw(false);
                        }
                    })
                }
            })
        }
    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/pages/category.blade.php ENDPATH**/ ?>