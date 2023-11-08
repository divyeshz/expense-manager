<?php $__env->startSection('title', 'Exp. Mgr. | Add Another Account'); ?>


<?php $__env->startSection('content'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Another Account</h1>
            
            <a class="btn btn-primary btn-icon-split d-none d-sm-inline-block shadow-sm" id="openAnotherAccountModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus-square"></i>
                </span>
                <span class="text">Another Account</span>
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Of Another Account Request</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-bordered" id="anotherAccountRequestListTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sender Name</th>
                                <th>Requested Account Name</th>
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
            $('#anotherAccountRequestListTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('anotherAccount.requestList')); ?>",
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'sender_name',
                        name: 'sender_name',
                    },
                    {
                        data: 'account_name',
                        name: 'account_name',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ]
            });

            // Reset Another Account Modal Form
            $(document).on("click", "#openAnotherAccountModal", function(event) {
                $("#addAnotherAccountModalForm .table").addClass('d-none');
                $('#addAnotherAccountModalForm #anotherAccountListTable').html('');
                $('#addAnotherAccountModalForm #email').val('');
                $("#addAnotherAccountModalForm #anotherAccountListTable #owner_id").val("");
                $('#addAnotherAccountModal').modal('show');
            });
        });

        // Function For Find List of Account and make a table and Add Send Request button
        function findAnotherAccount() {
            $.validator.addMethod("endsWithCom", function(value, element) {
                return value.endsWith(".com");
            }, "Please enter a valid email address ending with .com.");

            $("#addAnotherAccountModalForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        endsWithCom: true
                    },
                },
                messages: {
                    email: {
                        required: "We need your email address",
                        email: "Your email address must be in the format of name@domain.com",
                        endsWithCom: "Please enter a valid email address ending with .com."
                    },
                },
                submitHandler: function(form) {
                    $.ajax({
                        type: 'post',
                        data: $('#addAnotherAccountModalForm').serialize(),
                        url: "<?php echo e(route('anotherAccount.find')); ?>",
                        success: function(response) {
                            if (response.status == "200") {
                                var tr = '';
                                if (response.accounts.length > 0) {
                                    for (var i = 0; i < response.accounts.length; i++) {
                                        tr += `<tr>
                                                    <td>${response.accounts[i].name}</td>
                                                    <td>
                                                        <input type="hidden" name="owner_id" value="${response.accounts[i].owner_id}" id="owner_id">
                                                        <button type="button" class="btn btn-primary btn-sm" onclick=sendRequest(${response.accounts[i].id})>Send Request</button>
                                                    </td>
                                                </tr>`;
                                    }
                                }
                                $('#addAnotherAccountModal').modal('show');
                                $("#addAnotherAccountModalForm .table").removeClass('d-none');
                                $('#addAnotherAccountModalForm #anotherAccountListTable').html(tr);
                            } else {
                                toastr.error('' + response.message + '');
                            }
                        }

                    });
                }
            });
        }

        // Function For Send Request
        function sendRequest(id = "") {
            if (id != "") {
                let email = $("#addAnotherAccountModalForm #email").val();
                let owner_id = $("#addAnotherAccountModalForm #anotherAccountListTable #owner_id").val();
                $.ajax({
                    type: 'post',
                    data: {
                        email: email,
                        owner_id: owner_id,
                        id: id,
                        _token: "<?php echo e(csrf_token()); ?>"
                    },
                    url: "<?php echo e(route('anotherAccount.request')); ?>",
                    success: function(response) {
                        if (response.status == "200") {
                            $('#addAnotherAccountModal').modal('hide');
                            $("#addAnotherAccountModalForm .table").addClass('d-none');
                            $('#addAnotherAccountModalForm #anotherAccountListTable').html("");
                            toastr.success('' + response.message + '');
                        } else {
                            toastr.error('' + response.message + '');
                        }
                    }
                });
            }
        }

        // Approve Request
        function approveRequest(id = "") {
            if (id > 0) {
                $.ajax({
                    type: 'post',
                    data: {
                        id: id,
                        _token: "<?php echo e(csrf_token()); ?>"
                    },
                    url: "<?php echo e(route('anotherAccount.approveRequest')); ?>",
                    success: function(response) {
                        if (response.status == "200") {
                            var anotherAccountRequestListTable = $('#anotherAccountRequestListTable').dataTable();
                            anotherAccountRequestListTable.fnDraw(false);

                            toastr.success('' + response.message + '');
                        } else {
                            toastr.error('' + response.message + '');
                        }
                    }
                })
            }
        }
    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/pages/anotherAccount.blade.php ENDPATH**/ ?>