<!-- Add Transaction Modal-->
<div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Add Transaction </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- Add Form Start --}}
            <form id="addTransactionModalForm" method="POST">
                {{-- Csrf Token --}}
                @csrf
                <div class="modal-body">

                    {{-- Hidden Field For Account --}}
                    <input type="hidden" class="d-none" name="account_id" id="account_id" value="">

                    {{-- Account Name Field Readonly --}}
                    <div class="form-group">
                        <input type="text" readonly value="{{ $account->name }}" class="form-control" id="">
                    </div>

                    {{-- Amount Field --}}
                    <div class="form-group">
                        <input type="number" name="amount" class="form-control" id="amount"
                            placeholder="Enter Amount">
                    </div>

                    {{-- Transaction Field --}}
                    <div class="form-group">
                        <select class="form-control" id="transaction_type" name="transaction_type"
                            aria-label="Default select example">
                            <option selected disabled value="">Select Transaction Type</option>
                            <option value="1">Income</option>
                            <option value="0">Expense</option>
                        </select>
                    </div>

                    {{-- Category Dropdown --}}
                    <div class="form-group">
                        <select class="form-control" id="category" name="category[]" multiple
                            aria-label="Default select example">
                            <option disabled value="">Select Category</option>
                            @forelse ($category as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @empty
                                <option selected disabled value="">No Category Found</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Description Textarea --}}
                    <div class="form-group">
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                    </div>

                    {{-- Transfer checkbox --}}
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input is_transfer " name="is_transfer" type="checkbox"
                                value="1" id="flexCheckDefault">
                            <label class="custom-control-label" for="flexCheckDefault"> Transfer </label>
                        </div>
                    </div>

                    {{-- Receiver Dropdown --}}
                    <div class="form-group receiver-form-group">
                        <select class="form-control d-none" id="receiver" name="receiver"
                            aria-label="Default select example">
                            <option disabled selected value="">Select Receiver</option>
                            @forelse ($allAccount as $allAcc)
                                @if ($account->id != $allAcc->id)
                                    <option value="{{ $allAcc->id }}">{{ $allAcc->name }}</option>
                                @endif
                            @empty
                                <option selected disabled value="">No Receiver Account Found</option>
                            @endforelse
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    {{-- Cancel Button --}}
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    {{-- Submit Button --}}
                    <button type="submit" onclick="addTransaction()" id="addTransactionModalSubmit"
                        class="btn btn-primary">Save</button>
                </div>
            </form>
            {{-- Add Form End --}}

        </div>
    </div>
</div>

<!-- Edit Transaction Modal-->
<div class="modal fade" id="editTransactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Edit Transaction </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- Edit Form Start --}}
            <form id="editTransactionModalForm" method="POST">
                @csrf
                <div class="modal-body">

                    {{-- Hidden Field For Edit Transaction Id --}}
                    <input type="hidden" class="d-none" id="edit_transaction_id" name="edit_transaction_id"
                        value="">

                    {{-- Hidden Field For Account --}}
                    <input type="hidden" class="d-none" name="account_id" id="account_id" value="">

                    {{-- Account Name Field Readonly --}}
                    <div class="form-group">
                        <input type="text" readonly value="{{ $account->name }}" class="form-control"
                            id="">
                    </div>

                    {{-- Amount Field --}}
                    <div class="form-group">
                        <input type="number" name="amount" class="form-control" id="amount"
                            placeholder="Enter Amount">
                    </div>

                    {{-- Transaction Type Field --}}
                    <div class="form-group transaction_type">
                        <select class="form-control " id="transaction_type" name="transaction_type"
                            aria-label="Default select example">
                            <option selected disabled value="">Select Transaction Type</option>
                            <option value="1">Income</option>
                            <option value="0">Expense</option>
                        </select>
                    </div>

                    {{-- Category Field --}}
                    <div class="form-group">
                        <select class="form-control" id="category" name="category[]" multiple
                            aria-label="Default select example">
                            <option disabled value="">Select Category</option>
                            @forelse ($category as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @empty
                                <option selected disabled value="">No Category Found</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Description Field --}}
                    <div class="form-group">
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                    </div>

                    {{-- Transfer Field --}}
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_transfer" class="custom-control-input is_transfer"
                                value="1" id="is_transfer">
                            <label class="custom-control-label" for="is_transfer">Transfer</label>
                        </div>
                    </div>

                    {{-- Receiver Field --}}
                    <div class="form-group receiver-form-group">
                        <select class="form-control d-none receiver" id="receiver" name="receiver"
                            aria-label="Default select example">
                            <option selected disabled value="">Select Receiver</option>
                            @forelse ($allAccount as $allAcc)
                                @if ($account->id != $allAcc->id)
                                    <option value="{{ $allAcc->id }}">{{ $allAcc->name }}</option>
                                @endif
                            @empty
                                <option selected disabled value="">No Receiver Account Found</option>
                            @endforelse
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    {{-- Cancel Button --}}
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    {{-- Submit Button --}}
                    <button type="submit" onclick="editTransaction()" id="editTransactionModalSubmit"
                        class="btn btn-primary">Save</button>
                </div>
            </form>
            {{-- Edit Form End --}}

        </div>
    </div>
</div>
