<!-- Add Account Modal-->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Account</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {{-- Add Account Form --}}
            <form id="addAccountModalForm" method="post">
                @csrf
                <div class="modal-body">

                    {{-- Account Holder Name --}}
                    <div class="form-group">
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Account Holder Name" value="">
                    </div>

                    {{-- Account Number --}}
                    <div class="form-group ">
                        <input type="text" name="account_number" class="form-control" id="account_number"
                            placeholder="Account Number">
                    </div>

                    {{-- Save And Cancel Button --}}
                    <div class="modal-footer">
                        <button type="submit" onclick="addAccount()" id="AccountModalSubmit" class="btn btn-primary ">
                            Save
                        </button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Account Modal-->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- Edit Account Form --}}
            <form id="editAccountModalForm" method="post">
                @csrf
                <div class="modal-body">

                    {{-- Name --}}
                    <div class="form-group">
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="Account Holder Name" value="">
                    </div>

                    {{-- Account Number --}}
                    <div class="form-group ">
                        <input type="text" name="account_number" class="form-control" id="account_number"
                            placeholder="Account Number">
                    </div>

                    {{-- hidden edit account id --}}
                    <input type="hidden" id="edit_account_id" name="edit_account_id" class="form-control">
                </div>

                {{-- Save And Cancel Button --}}
                <div class="modal-footer">
                    <button type="submit" onclick="editAccount()" class="btn btn-primary ">Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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

            {{-- Add Balance Form --}}
            <form id="addBalanceModalForm" method="POST">
                @csrf
                <div class="modal-body">

                    {{-- Balance Amount --}}
                    <div class="form-group">
                        <input type="text" name="balance" class="form-control" id="balance"
                            placeholder="Balance Amount">
                    </div>

                    {{-- Add Balance Account Id hidden --}}
                    <input type="hidden" id="add_balance_account_id" name="add_balance_account_id"
                        class="form-control add_balance_account_id">
                </div>

                {{-- Cancel And Save Button --}}
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" onclick="addAccountBalance()" id="addBalanceModalSubmit"
                        class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
