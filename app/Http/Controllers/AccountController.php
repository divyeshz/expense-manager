<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;

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
}
