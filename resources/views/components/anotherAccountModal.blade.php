<!-- Add Another Account Modal-->
<div class="modal fade" id="addAnotherAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Another Account</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- Add Another Account Form --}}
            <form id="addAnotherAccountModalForm" method="post">
                @csrf
                <div class="modal-body">

                    {{-- Email --}}
                    <div class="form-group">
                        <input type="email" name="email" class="form-control " id="email" placeholder="Email">
                    </div>

                    {{-- Another Account list Table --}}
                    <table class="table table-bordered table-sm d-none" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="anotherAccountListTable">

                        </tbody>
                    </table>
                </div>

                {{-- Find Account And Cancel Button --}}
                <div class="modal-footer">
                    <button type="submit" onclick="findAnotherAccount()" id="addAnotherAccountModalSubmit"
                        class="btn btn-primary ">
                        Find Account
                    </button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
