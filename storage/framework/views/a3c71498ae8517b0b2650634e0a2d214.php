<!-- Transaction Modal-->
<div class="modal fade" id="TransactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">  </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            
            <form id="TransactionModalForm" method="POST">
                
                <?php echo csrf_field(); ?>
                <div class="modal-body">

                    
                    <input type="hidden" class="d-none" id="edit_transaction_id" name="edit_transaction_id">

                    
                    <input type="hidden" class="d-none" name="account_id" id="account_id" value="<?php echo e($account->id); ?>">

                    
                    <div class="form-group">
                        <input type="text" readonly value="<?php echo e($account->name); ?>" class="form-control" id="">
                    </div>

                    
                    <div class="form-group">
                        <input type="number" name="amount" class="form-control" id="amount"
                            placeholder="Enter Amount">
                    </div>

                    
                    <div class="form-group">
                        <select class="form-control" id="transaction_type" name="transaction_type"
                            aria-label="Default select example">
                            <option selected disabled value="">Select Transaction Type</option>
                            <option value="1">Income</option>
                            <option value="0">Expense</option>
                        </select>
                    </div>

                    
                    <div class="form-group">
                        <select class="form-control" id="category" name="category[]" multiple
                            aria-label="Default select example">
                            <option disabled value="">Select Category</option>
                            <?php $__empty_1 = true; $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option selected disabled value="">No Category Found</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    
                    <div class="form-group">
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                    </div>

                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="is_transfer" name="is_transfer" type="checkbox"
                                value="1" >
                            <label class="custom-control-label" for="is_transfer"> Transfer </label>
                        </div>
                    </div>

                    
                    <div class="form-group receiver-form-group">
                        <select class="form-control d-none receiver" id="receiver" name="receiver"
                            aria-label="Default select example">
                            <option disabled selected value="">Select Receiver</option>
                            <?php $__empty_1 = true; $__currentLoopData = $allAccount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allAcc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($account->id != $allAcc->id): ?>
                                    <option value="<?php echo e($allAcc->id); ?>"><?php echo e($allAcc->name); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option selected disabled value="">No Receiver Account Found</option>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    
                    <button type="submit" onclick="saveTransaction()" id="TransactionModalSubmit"
                        class="btn btn-primary">Save</button>
                </div>
            </form>
            

        </div>
    </div>
</div>
<?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/components/transactionModal.blade.php ENDPATH**/ ?>