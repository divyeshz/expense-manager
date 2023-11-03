{{-- Extends MainLayout --}}
@extends('layouts.authLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Forgot Password')

{{-- Content Start --}}
@section('content')

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                    <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                        and we'll send you a link to reset your password!</p>
                                </div>

                                {{-- Forgot Password Form --}}
                                <form class="user" id="forgotPasswordForm" action="{{ route('forgotPassword') }}"
                                    method="post">
                                    {{-- Csrf --}}
                                    @csrf

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <input name="email" id="email" type="email"
                                            class="form-control form-control-user" placeholder="Enter Email Address...">
                                    </div>

                                    {{-- Reset Password Button --}}
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>

                                <hr>

                                {{-- Create an Account Button --}}
                                <div class="text-center">
                                    <a class="small" href="{{ route('registrationForm') }}">Create an Account!</a>
                                </div>

                                {{-- Already have an account Button --}}
                                <div class="text-center">
                                    <a class="small" href="{{ route('loginForm') }}">Already have an account? Login!</a>
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

            // Validate Forgot Password Form
            $("#forgotPasswordForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        endsWithCom: true
                    }
                },
                messages: {
                    email: {
                        required: "We need your email address to contact you",
                        email: "Your email address must be in the format of name@domain.com",
                        endsWithCom: "Please enter a valid email address ending with .com."
                    }
                }
            });
        });
    </script>

@endsection
