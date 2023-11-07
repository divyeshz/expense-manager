{{-- Extends MainLayout --}}
@extends('layouts.mainLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Account')

{{-- Content Start --}}
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Accounts</h1>
            {{-- Add Account Button --}}
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
                    {{-- Accounts list Table --}}
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

@endsection
{{-- Content end --}}

{{-- Js Content Start --}}
@section('jsContent')

    <script>
        $(document).ready(function() {

            $('#accountListTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('account.list') }}",
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

            $(document).on("click", "#openAccountModal", function(event) {
                $("#addAccountModalForm #name").val('');
                $("#addAccountModalForm #account_number").val('');
                $('#addAccountModal').modal('show');
            });

        });

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
                        url: "{{ route('addBalance') }}",
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

        function addAccount() {
            $("#addAccountModalForm").validate({
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
                    $.ajax({
                        type: 'post',
                        data: $('#addAccountModalForm').serialize(),
                        url: "{{ route('account.save') }}",
                        success: function(response) {
                            var accountListTable = $('#accountListTable').dataTable();
                            accountListTable.fnDraw(false);

                            if (response.status == "200") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            $('#addAccountModal').modal('hide');
                        }

                    });
                }
            });
        }

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
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('account.delete') }}",
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

        function viewAccount(id = "") {
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "{{ route('account.view') }}",
                success: function(response) {
                    $('#editAccountModalForm #name').val(response.name);
                    $('#editAccountModalForm #account_number').val(response.account_number);;
                    $('#editAccountModalForm #edit_account_id').val(response.id);
                }
            })
        }

        function editAccount() {
            $("#editAccountModalForm").validate({
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
                    $.ajax({
                        type: 'post',
                        data: $('#editAccountModalForm').serialize(),
                        url: "{{ route('account.edit') }}",
                        success: function(response) {
                            var accountListTable = $('#accountListTable').dataTable();
                            accountListTable.fnDraw(false);

                            if (response.status == "200") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            $('#editAccountModal').modal('hide');
                        }

                    });
                }
            });
        }
    </script>

@endsection
{{-- Js Content End --}}
