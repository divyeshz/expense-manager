<?php $__env->startSection('title', 'Exp. Mgr. | Account'); ?>


<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Accounts</h1>
            
            <a class="btn btn-primary btn-icon-split d-none d-sm-inline-block shadow-sm" id="openAccountModal">
                <span class="icon text-white-50">
                    <i class="fas fa-solid fa-user-plus"></i>
                </span>
                <span class="text">Add Account</span>
            </a>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Of Accounts</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-bordered" id="accountListTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Account Number</th>
                                <th>Balance</th>
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
            $('#accountListTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('account.list')); ?>",
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'account_number',
                        name: 'Account Number',
                    },
                    {
                        data: 'balance',
                        name: 'Balance',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ]
            });

            // Reset Account Modal Form and open Account Modal
            $(document).on("click", "#openAccountModal", function(event) {
                $("#AccountModalForm #name").val('');
                $("#AccountModalForm #account_number").val('');
                $("#AccountModalForm #edit_account_id").val('');
                $('#AccountModal .modal-title').text('Add Account');
                $('#AccountModal').modal('show');
            });

        });

        // View Account Ajax call First Validate After That Put Data Into Modal
        function viewAccount(id = "") {
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "<?php echo e(route('account.view')); ?>",
                success: function(response) {
                    $("#AccountModalForm #name").val(response.name);
                    $("#AccountModalForm #account_number").val(response.account_number);;
                    $("#AccountModalForm #edit_account_id").val(response.id);
                    $('#AccountModal .modal-title').text('Edit Account');
                    $('#AccountModal').modal('show');
                }
            })
        }

        // Function For Add & Edit Account Using Same Modal Besed on Condition
        function saveAccount() {
            let edit_account_id = $("#AccountModalForm #edit_account_id").val();

            $("#AccountModalForm").validate({
                rules: {
                    name: "required",
                    account_number: {
                        required: true,
                        number: true,
                        minlength: 8,
                        maxlength: 8
                    }
                },
                messages: {
                    name: "Please Specify your name",
                    account_number: {
                        required: "Please provide a Account Number",
                        number: "Please enter a valid Number.",
                        minlength: "Your Account Number must be 8 digits long",
                        maxlength: "Your Account Number must be 8 digits long"
                    }
                },
                submitHandler: function(form) {
                    if (edit_account_id == "") {
                        $.ajax({
                            type: 'post',
                            data: $('#AccountModalForm').serialize(),
                            url: "<?php echo e(route('account.save')); ?>",
                            success: function(response) {
                                var accountListTable = $('#accountListTable').dataTable();
                                accountListTable.fnDraw(false);

                                if (response.status == "200") {
                                    toastr.success('' + response.message + '');
                                } else {
                                    toastr.error('' + response.message + '');
                                }
                                $('#AccountModal').modal('hide');
                            }

                        });
                    } else {
                        $.ajax({
                            type: 'post',
                            data: $('#AccountModalForm').serialize(),
                            url: "<?php echo e(route('account.edit')); ?>",
                            success: function(response) {
                                var accountListTable = $('#accountListTable').dataTable();
                                accountListTable.fnDraw(false);

                                if (response.status == "200") {
                                    toastr.success('' + response.message + '');
                                } else {
                                    toastr.error('' + response.message + '');
                                }
                                $('#AccountModal').modal('hide');
                            }

                        });
                    }
                }
            });
        }

        // Delete Account Ajax call First Validate After That Submit In Response Show Toast Message And ReDraw Datatable
        function deleteAccount(id = "") {
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
                        url: "<?php echo e(route('account.delete')); ?>",
                        success: function(response) {
                            var accountListTable = $('#accountListTable').dataTable();
                            accountListTable.fnDraw(false);

                            if (response.status == "200") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            $('#deleteAccountModal').modal('hide');
                        }
                    })
                }
            });
        }

        // Function for Add initial account balance
        function addAccountBalance() {
            $("#addBalanceModalForm").validate({
                rules: {
                    balance: {
                        required: true,
                        number: true,
                    }
                },
                messages: {
                    balance: {
                        required: "Please Provide Balance Amount In Number",
                        number: "Please enter a valid Number."
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        type: 'POST',
                        data: $('#addBalanceModalForm').serialize(),
                        url: "<?php echo e(route('addBalance')); ?>",
                        success: function(response) {
                            var accountListTable = $('#accountListTable').dataTable();
                            accountListTable.fnDraw(false);

                            if (response.status == "200") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            $('#addBalanceModal').modal('hide');

                        }

                    });
                }
            });
        }

    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/pages/account.blade.php ENDPATH**/ ?>