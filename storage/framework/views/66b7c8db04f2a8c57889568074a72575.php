<?php $__env->startSection('title', 'Exp. Mgr. | Transaction'); ?>


<?php $__env->startSection('content'); ?>

    <?php if ($__env->exists('components.transactionModal')) echo $__env->make('components.transactionModal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Transaction of <?php echo e($account->name); ?></h1>
            
            <button class="btn btn-primary btn-icon-split d-none d-sm-inline-block shadow-sm" id="openTransactionModal"
                onclick="$('#TransactionModalForm #account_id').val('<?php echo e($account->id); ?>');">
                <span class="icon text-white-50">
                    <i class="fas far fa-file-alt"></i>
                </span>
                <span class="text">Add Transaction</span>
            </button>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transaction Of Transaction</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <table class="table table-bordered" id="TransactionListTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount In &#8377;</th>
                                <th>Description</th>
                                <th>Transaction Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
                <a href="<?php echo e(route('account')); ?>" type="submit" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <!-- Total Account Balance Card  -->
        <div class="row justify-content-end balance_card">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Account Balance</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">&#8377;
                                    <span id="account_balance"> <?php echo e($account->balance); ?>  </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

<?php $__env->stopSection(); ?>



<?php $__env->startSection('jsContent'); ?>
    <script>
        $(document).ready(function() {

            var currentUrl = window.location.pathname;
            var segments = currentUrl.split('/');
            var transaction_id = segments[segments.length - 1];

            updateAccountBalance(transaction_id);

            // make yajra Table
            $('#TransactionListTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(route('transaction.list')); ?>",
                    type: 'GET',
                    data: {
                        id: transaction_id,
                    },
                },
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'amount_inr',
                        name: 'amount_inr',
                    },
                    {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'transaction_type',
                        name: 'transaction_type',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                columnDefs: [{
                        targets: 1,
                        createdCell: function(td, cellData, rowData, row, col) {
                            var transactionTypeValue = rowData['transaction_type'];

                            if (transactionTypeValue === 'Income') {
                                $(td).addClass('Income');
                            } else if (transactionTypeValue === 'Expense') {
                                $(td).addClass('Expense');
                            }else if (transactionTypeValue === 'Transfer') {
                                $(td).addClass('Transfer');
                            }
                        }
                    },
                    {
                        targets: 3,
                        createdCell: function(td, cellData, rowData, row, col) {
                            var transactionTypeValue = rowData['transaction_type'];

                            if (transactionTypeValue === 'Income') {
                                $(td).addClass('Income');
                            } else if (transactionTypeValue === 'Expense') {
                                $(td).addClass('Expense');
                            }else if (transactionTypeValue === 'Transfer') {
                                $(td).addClass('Transfer');
                            }
                        }
                    }
                ]
            });

            // Reset Transaction Modal Form And open Modal
            $(document).on("click", "#openTransactionModal", function(event) {
                $("#TransactionModalForm #amount").val('');
                $("#TransactionModalForm #edit_transaction_id").val('');
                $("#TransactionModalForm #transaction_type")[0].selectedIndex = 0;
                $("#TransactionModalForm #is_transfer")[0].checked = false;
                $("#TransactionModalForm #description").val('');
                $("#TransactionModalForm #receiver")[0].selectedIndex = 0;
                $("#TransactionModalForm #category")[0].selectedIndex = 0;
                showReciver();
                $('#TransactionModal .modal-title').text('Add Transaction');
                $('#TransactionModal').modal('show');
            });

            // Add Error To Transaction Type Field
            $("#transaction_type").change(function() {
                if ($('#is_transfer').is(':checked')) {
                    if ($(this).val() == '0' && ($("#transaction_type").hasClass('error'))) {
                        $("#transaction_type").removeClass('error');
                        $("#transaction_type-error").remove();
                    } else {
                        if ($(this).val() == '1' && (!$("#transaction_type").hasClass('error'))) {
                            $("#transaction_type").addClass('error');
                            let errorLabel =
                                `<label id="transaction_type-error" class="error" for="transaction_type">Please Select Valid Transaction Type </label>`;
                            $(errorLabel).insertAfter("#transaction_type");
                        }
                    }
                }
            });

            // On click Transfer CheckBox Call This Two Function
            $(document).on("click", "#is_transfer", function() {
                //  Transaction Type Validate For Transfer
                ValidateTransactionType();

                // Show Reciver Div
                showReciver();
            });

        });

        // Function For Add & Edit Transaction Using Same Modal Besed on Condition
        function saveTransaction() {
            let edit_transaction_id = $("#TransactionModalForm #edit_transaction_id").val();
            let account_id = $("#TransactionModalForm #account_id").val();

            $("#TransactionModalForm").validate({
                rules: {
                    account_id: "required",
                    amount: {
                        required: true,
                        number: true,
                    },
                    transaction_type: {
                        required: function() {
                            if ($('#is_transfer:checked').length > 0) {
                                return true;
                            }
                            return true;
                        }
                    },
                    receiver: {
                        required: "#flexCheckDefault:checked",
                    }
                },
                messages: {
                    amount: {
                        required: "Please Provide Amount In Number",
                        number: "Please enter a valid Number."
                    },
                    transaction_type: {
                        required: "Please select a Transaction Type.",
                    },
                    receiver: "Please Select Receiver",
                },
                submitHandler: function(form) {
                    if (edit_transaction_id == "") {
                        $.ajax({
                            type: 'post',
                            dataType: 'json',
                            data: $('#TransactionModalForm').serialize(),
                            url: "<?php echo e(route('transaction.save')); ?>",
                            success: function(response) {
                                if (response.status == "200") {
                                    toastr.success('' + response.message + '');
                                    updateAccountBalance(account_id);
                                } else {
                                    toastr.error('' + response.message + '');
                                }
                                var TransactionListTable = $('#TransactionListTable').dataTable();
                                TransactionListTable.fnDraw(false);

                                $('#TransactionModal').modal('hide');
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'post',
                            dataType: 'json',
                            data: $('#TransactionModalForm').serialize(),
                            url: "<?php echo e(route('transaction.edit')); ?>",
                            success: function(response) {
                                if (response.status == "200") {
                                    toastr.success('' + response.message + '');
                                    updateAccountBalance(account_id);
                                } else {
                                    toastr.error('' + response.message + '');
                                }
                                $('#TransactionModal').modal('hide');
                                var TransactionListTable = $('#TransactionListTable').dataTable();
                                TransactionListTable.fnDraw(false);
                            }

                        });
                    }
                }
            });
        }

        // View Transaction Ajax call In Response Put Data Into Edit Modal And Show Edit Modal
        function viewTransaction(id = "") {
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                dataType: 'json',
                url: "<?php echo e(route('transaction.view')); ?>",
                success: function(response) {
                    $("#TransactionModalForm #category")[0].selectedIndex = 0;
                    if (response.status == '200') {
                        var transaction_type = response.transaction.transaction_type;
                        var is_transfer = response.transaction.is_transfer;
                        var receiver_id = response.transaction.receiver_id;

                        if (response.transaction_categorie.length > 0) {
                            for (var i = 0; i < response.transaction_categorie.length; i++) {
                                var categorie_id = response.transaction_categorie[i].id;
                                $("#TransactionModalForm #category").find('option[value="' + categorie_id +
                                    '"]').prop('selected', true);
                            }
                        }

                        $("#TransactionModalForm #edit_transaction_id").val(response.transaction.id);
                        $("#TransactionModalForm #account_id").val(response.transaction.account_id);
                        $("#TransactionModalForm #amount").val(response.transaction.amount);

                        if (transaction_type != "" && transaction_type != null) {
                            $("#TransactionModalForm #transaction_type").find('option[value="' +
                                    transaction_type + '"]')
                                .prop('selected', true);
                        } else {
                            $("#TransactionModalForm #transaction_type").find('option[value=""]').prop(
                                'selected',
                                true);
                        }

                        if (is_transfer == '1' && is_transfer != "0") {
                            $('#TransactionModalForm #is_transfer[value="' + is_transfer + '"]').prop(
                                'checked', true);
                        } else {
                            $('#TransactionModalForm #is_transfer').prop('checked', false);
                        }
                        showReciver();

                        if (receiver_id != "" && receiver_id != null) {
                            $("#TransactionModalForm #receiver").find('option[value="' + receiver_id + '"]')
                                .prop('selected', true);
                        } else {
                            $("#TransactionModalForm #receiver").find('option[value=""]').prop('selected',
                                true);
                        }

                        $("#TransactionModalForm #description").val(response.transaction.description);
                        $('#TransactionModal .modal-title').text('Edit Transaction');
                        $('#TransactionModal').modal('show');
                    } else {
                        toastr.error('' + response.message + '');
                    }
                }
            })
        }

        // Delete Transaction Ajax call First Show Sweet Alert After That Submit In Response Show Toast Message And Rerender Transaction List
        function deleteTransaction(id = "") {
            let account_id = $("#TransactionModalForm #account_id").val();
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
                        url: "<?php echo e(route('transaction.delete')); ?>",
                        success: function(response) {
                            if (response.status == "200") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            updateAccountBalance(account_id);
                            var TransactionListTable = $('#TransactionListTable').dataTable();
                            TransactionListTable.fnDraw(false);
                        }
                    });
                }
            })
        }

        // Show Reciver Function
        function showReciver() {
            if ($('#is_transfer').is(':checked')) {
                if ($(".receiver-form-group .form-control").hasClass('d-none')) {
                    $(".receiver-form-group .form-control").removeClass('d-none');
                }
            } else {
                if (!$(".receiver-form-group .form-control").hasClass('d-none')) {
                    $(".receiver-form-group .form-control").addClass('d-none');
                }
            }
        }

        // function For Validate Transaction Type
        function ValidateTransactionType() {
            let edit_transaction_id = $("#editTransactionModalForm #edit_transaction_id").val();
            if (edit_transaction_id == "") {
                let transaction_type_value = $("#transaction_type option:selected").val();
                if (transaction_type_value == '1' && (!$("#transaction_type").hasClass(
                        'error'))) {
                    $("#transaction_type").addClass('error');
                    let errorLabel =
                        `<label id="transaction_type-error" class="error" for="transaction_type">Please Select Valid Transaction Type </label>`;
                    $(errorLabel).insertAfter("#transaction_type");
                } else {
                    if (!$("#transaction_type").hasClass('error') && (transaction_type_value !=
                            '0')) {
                        $("#transaction_type").addClass('error');
                        let errorLabel =
                            `<label id="transaction_type-error" class="error" for="transaction_type">Please Select Transaction Type </label>`;
                        $(errorLabel).insertAfter("#transaction_type");
                    }
                }
            }
        }

        function updateAccountBalance(id) {
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                dataType: 'json',
                url: "<?php echo e(route('accountBalance')); ?>",
                success: function(response) {
                    if(response.status == "200"){
                        $(".container-fluid .balance_card #account_balance").text(response.balance);
                    }
                }
            })
        }
    </script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/pages/transaction.blade.php ENDPATH**/ ?>