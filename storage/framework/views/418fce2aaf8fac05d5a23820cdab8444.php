<!-- Account Modal-->
<div class="modal fade" id="AccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">  </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            
            <form id="AccountModalForm" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-body">

                    
                    <input type="hidden" id="edit_account_id" name="edit_account_id" class="form-control d-none">

                    
                    <div class="form-group">
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Account Holder Name" value="">
                    </div>

                    
                    <div class="form-group ">
                        <input type="text" name="account_number" class="form-control" id="account_number"
                            placeholder="Account Number">
                    </div>

                    
                    <div class="modal-footer">
                        <button type="submit" onclick="saveAccount()" id="AccountModalSubmit" class="btn btn-primary ">
                            Save
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Balance Modal-->
<div class="modal fade" id="addBalanceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Balance</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            
            <form id="addBalanceModalForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">

                    
                    <div class="form-group">
                        <input type="text" name="balance" class="form-control" id="balance"
                            placeholder="Balance Amount">
                    </div>

                    
                    <input type="hidden" id="add_balance_account_id" name="add_balance_account_id"
                        class="form-control add_balance_account_id">
                </div>

                
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" onclick="addAccountBalance()" id="addBalanceModalSubmit"
                        class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/ztlab60/ExpenseManager/resources/views/components/accountModal.blade.php ENDPATH**/ ?>