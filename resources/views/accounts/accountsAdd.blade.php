@extends('layouts.mainLayout')

@section('title', 'Expense Manager | Accounts List')

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">Accounts</h1>

        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="p-1">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Add Account</h1>
                    </div>

                    <form class="user" id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <input type="text" name="name" class="form-control " id="name"
                                placeholder="Account Holder Name">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control " id="email"
                                placeholder="Email Address">
                        </div>
                        <div class="form-group ">
                            <input type="text" name="phone" class="form-control " id="phone"
                                placeholder="Phone Number">
                        </div>
                        <div class="form-group ">
                            <input type="text" name="account_number" class="form-control" id="account_number"
                                placeholder="Account Number">
                        </div>

                        <button type="submit" class="btn btn-primary ">
                            Save
                        </button>
                        <a href="{{ route('accounts.list') }}" type="submit" class="btn btn-secondary ">
                            Cancel
                        </a>

                    </form>

                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
