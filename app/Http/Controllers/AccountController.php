<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AccountRequests;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    // Return Account View Page
    public function account()
    {
        return view('pages.account');
    }

    // Return Current User Account List to Ajax Call
    public function accountList(Request $request)
    {
        if ($request->ajax()) {
            $data = Account::where('owner_id', auth()->id())->get();
            return Datatables::of($data)
                ->addColumn('#', function () {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->addColumn('action', function ($row) {

                    $transactionUrl = route('transaction', $row->id);

                    $actionBtn = '<button type="submit" data-toggle="modal" data-target="#editAccountModal" class="btn btn-primary editAccountModal" onclick=viewAccount(' . $row->id . ')>Edit</button>
                    <button type="submit" class="btn btn-danger deleteAccountModal" onclick="deleteAccount(' . $row->id . ')">Delete</button>
                    <button data-toggle="modal" data-target="#addBalanceModal" onclick="$(\'#add_balance_account_id\').val(' . $row->id . ')" class="btn btn-info">Add Balance</button>
                    <a href="' . $transactionUrl . '" class="btn btn-warning">View Transaction</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Delete Account And Return Response to Ajax Call
    public function accountDelete(Request $request)
    {
        $delete = Account::findOrFail($request->id)->delete();

        if ($delete) {
            $response = [
                'status' => '200',
                'message' => 'Account Deleted SuccessFully!!!'
            ];
        } else {
            $response = [
                'status' => '400',
                'message' => 'Account Deleted Failed!!!'
            ];
        }
        return $response;
    }

    // Return Specific Account Data For Edit
    public function accountView(Request $request)
    {
        return Account::findOrFail($request->id);
    }

    // Edit Account Data And Update Database Record And Return Response to Ajax Call
    public function accountEdit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account_number' => 'required',
            'edit_account_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account')->withErrors($validator);
        }

        $update = [
            'name' => $request->name,
            'account_number' => $request->account_number,
        ];

        $edit = Account::where('id', $request->edit_account_id)->where('owner_id', auth()->id())->update($update);

        if ($edit) {
            $response = [
                'status' => '200',
                'message' => 'Account updated SuccessFully!!!'
            ];
        } else {
            $response = [
                'status' => '400',
                'message' => ' Account updated failed!!!'
            ];
        }
        return $response;
    }

    // Save New Account Data Into Database And Return Response to Ajax Call
    public function accountSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account_number' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account')->withErrors($validator);
        }

        // store the data
        $add = Account::create([
            'name' => $request->name,
            'account_number' => $request->account_number,
            'owner_id' => auth()->id(),
        ]);

        if ($add) {
            $response = [
                'status' => '200',
                'message' => 'Account Created SuccessFully!!!'
            ];
        } else {
            $response = [
                'status' => '400',
                'message' => 'Account Created failed!!!'
            ];
        }
        return $response;
    }

    // Add Balance to Specific Account And Return Response to Ajax Call
    public function addBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'balance' => 'required',
            'add_balance_account_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account')->withErrors($validator);
        }

        $account = Account::findOrFail($request->add_balance_account_id);

        if ($account) {
            $account->balance += $request->balance;
            $account->save();

            $add_Transaction = Transaction::create([
                'account_id' => $request->add_balance_account_id,
                'transaction_type' => '1',
                'amount' => $request->balance,
                'description' => 'Add Account Balance',
                'transaction_by' => auth()->id()
            ]);

            $response = [
                'status' => '200',
                'message' => 'Balance Add SuccessFully!!!'
            ];
        } else {
            $response = [
                'status' => '400',
                'message' => 'Account Not Found!!!'
            ];
        }
        return $response;
    }


    /* Another Account function */

    // Return Another Account View Page
    public function anotherAccount()
    {
        return view('pages.anotherAccount');
    }

    // Return Another Account Request List table With yajra Datatable
    public function anotherAccountRequestList(Request $request)
    {
        $accountRequestsData = AccountRequests::where('account_owner_id', auth()->id())->get();
        $tableData = [];

        if (count($accountRequestsData) > 0) {
            foreach ($accountRequestsData as $key => $value) {
                $sender_id = $value->sender_id;
                $account_id = $value->account_id;
                $id = $value->id;
                $is_approved = $value->is_approved;
            }

            $accountData = Account::where('owner_id', auth()->id())->where('id', $account_id)->get();
            if (count($accountData) > 0) {
                foreach ($accountData as $key => $value) {
                    $accountName = $value->name;
                }
            }

            $senderAccountData = User::findOrFail($sender_id);
            $senderName = $senderAccountData->name;
            $tableData[] = [
                'id' => $id,
                'accountName' => $accountName,
                'senderName' => $senderName,
                'is_approved' => $is_approved,
            ];
        }

        if ($request->ajax()) {
            return Datatables::of($tableData)
                ->addColumn('#', function () {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->addColumn('sender_name', function ($row) {
                    return $row['senderName'];
                })
                ->addColumn('account_name', function ($row) {
                    return $row['accountName'];
                })
                ->addColumn('action', function ($row) {
                    $is_disabled = $row['is_approved'] == 0 ? "" : "disabled";
                    $actionBtn = '<button type="button" class="btn btn-success editAccountModal" ' . $is_disabled . ' onclick=approveRequest(' . $row['id'] . ')>Approve</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Return List of Account of Particular user
    public function findAnotherAccount(Request $request)
    {

        $findAnotherAccount = false;
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('anotherAccount')->withErrors($validator);
        }

        if (Auth::user()->email == $request->email) {
            $findAnotherAccount = false;
        } else {
            $user = User::where('email', $request->email)->first();
            $accounts = $user->accounts;
            if (count($accounts)) {
                $findAnotherAccount = true;
            }
        }

        if ($findAnotherAccount) {

            $response = [
                'status' => '200',
                'message' => 'Another Account Find SuccessFully!!!',
                'accounts' => $accounts
            ];
        } else {
            $response = [
                'status' => '400',
                'message' => 'Invaild Credential!!!'
            ];
        }
        return $response;
    }

    // Send the Request For Access Another Account
    public function requestAnotherAccount(Request $request)
    {
        // get the Request Account Data
        $accountData = Account::where('owner_id', $request->owner_id)->where('id', $request->id)->get();
        if (count($accountData) > 0) {
            foreach ($accountData as $key => $value) {
                $accountName = $value->name;
                $accountNumber = $value->account_number;
            }
        }

        // Chek If Account Alredy Exists
        $currentAccountData = Account::where('owner_id', auth()->id())->get();
        if (count($currentAccountData) > 0) {
            foreach ($currentAccountData as $key => $value) {
                $currentAccountId = $value->id;
                $currentAccountName = $value->name;
                $currentAccountNumber = $value->account_number;
                $currentAccountOwnerId = $value->owner_id;
                if($currentAccountName == $accountName && $currentAccountNumber == $accountNumber && $currentAccountOwnerId == auth()->id()){
                    return $response = [
                        'status' => '400',
                        'message' => 'Account Alredy Exists!!!'
                    ];
                }
            }
        }

        // chek If Request is Alredy Send
        $alredyRequestSend = AccountRequests::where([
            ['sender_id', auth()->id()],
            ['account_id', $request->id],
            ['account_owner_id', $request->owner_id],
        ])->get();

        if (count($alredyRequestSend) > 0) {
            $response = [
                'status' => '400',
                'message' => 'Request Alredy Submitted!!!'
            ];
        } else {
            if ($request->email != "" && $request->owner_id != "" && $request->id != "") {
                AccountRequests::create([
                    'sender_id' => auth()->id(),
                    'account_id' => $request->id,
                    'account_owner_id' => $request->owner_id,
                ]);

                $response = [
                    'status' => '200',
                    'message' => 'Request Sent SuccessFully!!!'
                ];
            } else {
                $response = [
                    'status' => '400',
                    'message' => 'Invaild Credential!!!'
                ];
            }
        }
        return $response;
    }

    // Approve the Request and Add that user data into Account table and make Approve status 1
    public function approveRequest(Request $request)
    {

        $RequestsData = AccountRequests::findOrFail($request->id);
        if ($RequestsData) {
            $accountData = Account::where('owner_id', $RequestsData->account_owner_id)->where('id', $RequestsData->account_id)->get();
            if (count($accountData) > 0) {
                foreach ($accountData as $key => $value) {
                    $accountName = $value->name;
                    $accountNumber = $value->account_number;
                }

                $edit = AccountRequests::where('id', $request->id)->update(["is_approved" => 1]);

                // store the data
                Account::create([
                    'name' => $accountName,
                    'account_number' => $accountNumber,
                    'owner_id' =>  $RequestsData->sender_id,
                ]);

                $response = [
                    'status' => '200',
                    'message' => 'Request Approved SuccessFully!!!'
                ];
            }
        } else {
            $response = [
                'status' => '400',
                'message' => 'Invalid Request!!!'
            ];
        }
        return $response;
    }
}
