@extends('layouts.mainLayout')

@section('title', 'Expense Manager | Accounts List')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">Accounts</h1>

            <a href="{{ route('accounts.add') }}" class="btn btn-primary btn-icon-split" style="right:25px;position: absolute;">
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
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Account Number</th>
                                <th>Balance</th>
                                <th>Create Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>d@gmail.com</td>
                                <td>1234567890</td>
                                <td>**** **** **** 4589</td>
                                <td>₹320,800</td>
                                <td>20/08/2023</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
