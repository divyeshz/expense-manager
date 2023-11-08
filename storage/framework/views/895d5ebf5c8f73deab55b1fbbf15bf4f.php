<?php if($msg = Session::get('success')): ?>
    <script>
        toastr.success('<?php echo e($msg); ?>');
    </script>
<?php endif; ?>

<?php if($msg = Session::get('error')): ?>
    <script>
        toastr.error('<?php echo e($msg); ?>');
    </script>
<?php endif; ?>

<?php if($msg = Session::get('info')): ?>
    <script>
        toastr.info('<?php echo e($msg); ?>');
    </script>
<?php endif; ?>

<?php if($msg = Session::get('warning')): ?>
    <script>
        toastr.warning('<?php echo e($msg); ?>');
    </script>
<?php endif; ?>
<?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/components/toastr.blade.php ENDPATH**/ ?>