
{{-- Extends MainLayout --}}
@extends('layouts.mainLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Change Password')

{{-- Content Start --}}
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">Password</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
            </div>
            <div class="card-body">

                {{-- Change Password Form --}}
                <form class="user" id="changePasswordForm" action="{{ route('changePassword') }}" method="POST">
                    {{-- Csrf --}}
                    @csrf

                    {{-- Current Password --}}
                    <div class="form-group">
                        <input type="password" name="old_password" class="form-control " id="old_password"
                            placeholder="Current Password">
                    </div>

                    {{-- New Password --}}
                    <div class="form-group">
                        <input type="password" name="new_password" class="form-control " id="new_password"
                            placeholder="New Password">
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="form-group">
                        <input type="password" name="confirm_new_password" class="form-control" id="confirm_new_password "
                            placeholder="Confirm New Password">
                    </div>

                    {{-- Save Button --}}
                    <button type="submit" class="btn btn-primary ">
                        Save
                    </button>

                    {{-- Cancel Button --}}
                    <a href="{{ route('dashboard') }}" type="submit" class="btn btn-secondary ">
                        Cancel
                    </a>

                </form>
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

            // Validate Change Password Form
            $("#changePasswordForm").validate({
                rules: {
                    old_password: {
                        required: true,
                        minlength: 6
                    },
                    new_password: {
                        required: true,
                        minlength: 6,
                    },
                    confirm_new_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#new_password"
                    }
                },
                messages: {
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    new_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long",
                    },
                    confirm_new_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long",
                        equalTo: "Please enter the same password as above"
                    }
                }
            });
        });
    </script>

@endsection
{{-- Js Content End --}}
