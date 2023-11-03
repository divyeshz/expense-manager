{{-- Extends MainLayout --}}
@extends('layouts.authLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Register')

{{-- Content Start --}}
@section('content')


    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>

                        {{-- Register Form --}}
                        <form class="user" id="registerForm" action="{{ route('register') }}" method="POST">
                            {{-- Csrf --}}
                            @csrf

                            {{-- User Name --}}
                            <div class="form-group">
                                <input type="text" name="name" class="form-control form-control-user" id="name"
                                    placeholder="User Name">
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user" id="email"
                                    placeholder="Email Address">
                            </div>

                            {{-- Password And Repeat Password --}}
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" name="password" class="form-control form-control-user"
                                        id="password" placeholder="Password">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="confirm_password" class="form-control form-control-user"
                                        id="confirm_password" placeholder="Repeat Password">
                                </div>
                            </div>

                            {{-- Register Account Button --}}
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>

                        </form>
                        <hr>
                        {{-- Already have an account Link --}}
                        <div class="text-center">
                            <a class="small" href="{{ route('loginForm') }}">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('jsContent')

    <script>
        $(document).ready(function() {

            $.validator.addMethod("endsWithCom", function(value, element) {
                return value.endsWith(".com");
            }, "Please enter a valid email address ending with .com.");

            // Validate Register Form
            $("#registerForm").validate({
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        email: true,
                        endsWithCom: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    }
                },
                messages: {
                    name: "Please specify your name",
                    email: {
                        required: "We need your email address to contact you",
                        email: "Your email address must be in the format of name@domain.com",
                        endsWithCom: "Please enter a valid email address ending with .com."
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    confirm_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long",
                        equalTo: "Please enter the same password as above"
                    },
                }
            });
        });
    </script>

@endsection
