<?php $__env->startSection('title', 'Exp. Mgr. | Reset Password'); ?>


<?php $__env->startSection('content'); ?>

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
                                    <h1 class="h4 text-gray-900 mb-2">Reset New Password!!</h1>
                                </div>

                                
                                <form class="user" id="resetPasswordForm" action="<?php echo e(route('resetPassword')); ?>"
                                    method="post">
                                    
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="prt_token" class="d-none form-control form-control-user"
                                        id="prt_token" value="<?php echo e($token); ?>">

                                    
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="password" placeholder="Enter New Password">
                                    </div>

                                    
                                    <div class="form-group">
                                        <input type="password" name="confirm_password"
                                            class="form-control form-control-user" id="confirm_password"
                                            placeholder="Confirm New Password">
                                    </div>

                                    
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('jsContent'); ?>

    <script>
        $(document).ready(function() {

            // Validate Reset Password Form
            $("#resetPasswordForm").validate({
                rules: {
                    prt_token: {
                        required: true,
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
                    password: {
                        required: "Please Provide a New password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    confirm_password: {
                        required: "Please Confirm New password",
                        minlength: "Your password must be at least 6 characters long",
                        equalTo: "Please enter the same password as above"
                    },
                }
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.authLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/auth/resetpassword.blade.php ENDPATH**/ ?>