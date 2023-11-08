{{-- Extends MainLayout --}}
@extends('layouts.mainLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Transaction')

{{-- Content Start --}}
@section('content')

    @includeIF('components.transactionModal')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Transaction of {{ $account->name }}</h1>
            {{-- Add Transaction Button --}}
            <button class="btn btn-primary btn-icon-split d-none d-sm-inline-block shadow-sm" id="openTransactionModal"
                onclick="$('#TransactionModalForm #account_id').val('{{ $account->id }}');">
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
                    {{-- Transaction list Table --}}
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Amount In &#8377;</th>
                                <th>Description</th>
                                <th>Transaction Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="TransactionList">
                        </tbody>
                    </table>
                </div>
                {{-- Back Button --}}
                <a href="{{ route('account') }}" type="submit" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
{{-- Content end --}}

{{-- Js Content Start --}}
@section('jsContent')
    <script>
        $(document).ready(function() {
            transactionList();

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

        // Get The Transaction List in response And Make Table Row and Attached into Table
        function transactionList() {
            var currentUrl = window.location.pathname;
            var segments = currentUrl.split('/');
            var transaction_id = segments[segments.length - 1];

            $.ajax({
                type: 'get',
                data: {
                    id: transaction_id,
                },
                dataType: 'json',
                url: "{{ route('transaction.list') }}",
                success: function(response) {
                    var tr = '';
                    var Total_balance = response.account.balance;
                    if (response.transaction.length > 0) {
                        for (var i = 0; i < response.transaction.length; i++) {

                            var id = response.transaction[i].id;
                            var amount = response.transaction[i].amount;

                            var description = (response.transaction[i].description != "" &&
                                    response.transaction[i].description != null) ? response.transaction[i]
                                .description : '-';

                            var receiver_id = (response.transaction[i].receiver_id != '' &&
                                    response.transaction[i].receiver_id != null) ? response.transaction[i]
                                .receiver_id : '-';

                            var is_transfer = response.transaction[i].is_transfer;
                            var transaction_type = response.transaction[i].transaction_type;
                            var transaction_type_class = (transaction_type == '1') ? 'Income' : 'Expense';
                            var transaction_type_html = "";

                            if (transaction_type == '0') {
                                if (receiver_id != '' && receiver_id != null && is_transfer == '1') {
                                    transaction_type_html += 'Transfer';
                                } else {
                                    transaction_type_html += 'Expense <i class="fas fa-arrow-up fa-sm"></i>';
                                }
                            }

                            if (transaction_type == '1') {
                                if (receiver_id != '' && receiver_id != null && is_transfer == '1') {
                                    transaction_type_html += 'Transfer';
                                } else {
                                    transaction_type_html +=
                                        'Income <i class="fas fa-arrow-down fa-sm"></i>';
                                }
                            }

                            tr += `<tr>
                                    <td class="${transaction_type_class}">
                                        ${amount}
                                    </td>
                                    <td>${description}</td>
                                    <td class="${transaction_type_class}" >${transaction_type_html}</td>
                                    <td><button type="submit" class="btn btn-primary" onclick=viewTransaction(${id})>Edit</button>
                                        <button type="submit" onclick=deleteTransaction(${id}) id="transactionDeleteBtn" class="btn btn-danger">Delete</button>
                                    </td>
                                 </tr>`;
                        }
                    } else {
                        tr += `<tr>
                                    <td colspan="4" class="text-center">No Transaction Found</td>
                                </tr>`;
                    }

                    var total_balance_tr = `<tr>
                                                <td colspan="3" class="text-right">Total</td>
                                                <td>${Total_balance}</td>
                                            </tr>`;
                    $('#TransactionList').html(tr).append(total_balance_tr);
                }
            });
        }

        // Function For Add & Edit Transaction Using Same Modal Besed on Condition
        function saveTransaction() {
            let edit_transaction_id = $("#TransactionModalForm #edit_transaction_id").val();

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
                            url: "{{ route('transaction.save') }}",
                            success: function(response) {
                                if (response.status == "200") {
                                    toastr.success('' + response.message + '');
                                    transactionList();
                                } else {
                                    toastr.error('' + response.message + '');
                                }
                                $('#TransactionModal').modal('hide');
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'post',
                            dataType: 'json',
                            data: $('#TransactionModalForm').serialize(),
                            url: "{{ route('transaction.edit') }}",
                            success: function(response) {
                                if (response.status == "200") {
                                    toastr.success('' + response.message + '');
                                } else {
                                    toastr.error('' + response.message + '');
                                }
                                $('#TransactionModal').modal('hide');
                                transactionList();
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
                url: "{{ route('transaction.view') }}",
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
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('transaction.delete') }}",
                        success: function(response) {
                            if (response.status == "200") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            transactionList();
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
    </script>

@endsection
{{-- Js Content End --}}
