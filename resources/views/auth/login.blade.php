{{-- Extends MainLayout --}}
@extends('layouts.authLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Login')

{{-- Content Start --}}
@section('content')

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>

                                {{-- Login Form --}}
                                <form id="loginForm" class="user" action="{{ route('login') }}" method="POST">
                                    {{-- Csrf --}}
                                    @csrf

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email"
                                            id="email" placeholder="Email Address">
                                    </div>

                                    {{-- Password --}}
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password"
                                            name="password" placeholder="Password">
                                    </div>

                                    {{-- Login Button --}}
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>

                                </form>
                                <hr>
                                {{-- Forgot Password Link --}}
                                <div class="text-center">
                                    <a class="small" href="{{ route('forgotPasswordForm') }}">Forgot Password?</a>
                                </div>

                                {{-- Create an Account Link --}}
                                <div class="text-center">
                                    <a class="small" href="{{ route('registrationForm') }}">Create an Account!</a>
                                </div>
                            </div>
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

            // Validate Login Form
            $("#loginForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        endsWithCom: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "We need your email address to contact you",
                        email: "Your email address must be in the format of name@domain.com",
                        endsWithCom: "Please enter a valid email address ending with .com."
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    }
                }
            });
        });
    </script>

@endsection
