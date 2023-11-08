<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class TransactionController extends Controller
{

    /* Return transaction Page View And With Other Data Like Login user Account, All User Account And List Of Category */
    public function transaction(string $id)
    {
        $account = Account::findOrFail($id);
        $allAccount = Account::where('owner_id', auth()->id())->get();
        $category = Category::get();
        return view('pages.transaction', compact('account', 'allAccount', 'category'));;
    }

    /* Fetch Transaction List And Currently Login user Account And return to ajaxcall */
    public function transactionList(Request $request)
    {
        $transaction = Transaction::where('account_id', $request->id)->where('transaction_by', auth()->id())->get();
        $tableData = [];

        if (count($transaction) > 0) {
            foreach ($transaction as $key => $value) {
                $id = $value['id'];
                $amount = $value['amount'];
                $description = ($value['description'] != "" && $value['description']) ? $value['description'] : '-';
                $receiver_id = ($value['receiver_id'] != "" && $value['receiver_id']) ? $value['receiver_id'] : '-';
                $is_transfer = $value['is_transfer'];
                $transaction_type = $value['transaction_type'];
                $transaction_type_name = "";

                if ($receiver_id != '' && $receiver_id != null && $is_transfer == '1') {
                    $transaction_type_name .= 'Transfer';
                } else {
                    $transaction_type_name .= ($transaction_type == '1') ? 'Income' : 'Expense';
                }

                $tableData[] = [
                    'id'                        => $id,
                    'amount'                    => $amount,
                    'description'               => $description,
                    'transaction_type_name'    => $transaction_type_name,
                ];
            }
        }

        if ($request->ajax()) {
            return Datatables::of($tableData)
                ->addColumn('#', function () {
                    static $counter = 0;
                    $counter++;
                    return $counter;
                })
                ->addColumn('amount_inr', function ($row) {
                    return $row['amount'];
                })
                ->addColumn('description', function ($row) {
                    return $row['description'];
                })
                ->addColumn('transaction_type', function ($row) {
                    return html_entity_decode($row['transaction_type_name']);
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <button type="submit" class="btn btn-primary" onclick="viewTransaction(' . $row['id'] . ')">Edit</button> <button type="submit" onclick="deleteTransaction(' . $row['id'] . ')" class="btn btn-danger">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['transaction_type', 'action'])
                ->make(true);
        }
    }

    /* Fetch Transaction And List of Transaction Categorie And return to ajaxcall */
    public function transactionView(Request $request)
    {
        $transaction = Transaction::findOrFail($request->id);
        $transaction_categorie = $transaction->categories;
        $account_id = $transaction->account_id;
        $is_transfer = $transaction->is_transfer;
        $receiver_id = $transaction->receiver_id;
        $transaction_type = $transaction->transaction_type;

        if ($transaction_type == '1' && $is_transfer == '1' && $receiver_id != '' && $account_id != "") {
            $response = [
                'status'    => '400',
                'message'   => "You Can't Update This Transaction!!!",
            ];
        } else {
            if ($transaction) {
                $response = [
                    'status'                => '200',
                    'message'               => 'SuccessFully Get The Transaction Data',
                    'transaction'           => $transaction,
                    'transaction_categorie' => $transaction_categorie,
                ];
            } else {
                $response = [
                    'status'    => '400',
                    'message'   => 'Failed To Get Transaction Data'
                ];
            }
        }
        return json_encode($response);
    }

    /* Save Transaction Into Database */
    public function transactionSave(Request $request)
    {

        // Validate Data
        $request->validate([
            'account_id'        => 'required|integer',
            'amount'            => 'required|numeric',
            'transaction_type'  => 'required',
        ]);

        $save = false; // flag Variable for Store the data into Database

        $is_transfer = $request->is_transfer != "" ? $request->is_transfer : '0';
        $transaction_type = $request->transaction_type;
        $account_id = $request->account_id;
        $amount = $request->amount;
        $description = $request->description;
        $receiver = $request->receiver;

        /* If Transaction Type is Expense and Transfer is 1 {true} Than go into This If condition Or Go to Else */
        if ($transaction_type == '0' && $is_transfer == '1') {

            /* Call the Private Function For Save The Data for Transaction type Transfer */
            $SaveTransactionForTransfer = $this->SaveTransactionForTransfer($request);

            if ($SaveTransactionForTransfer == false) {
                $response = [
                    'status'    => '400',
                    'message'   => 'Not Enough Balance!!!'
                ];
                return json_encode($response);
            }
            $save = true;
        } else {

            /* If Select Wrong Transaction Type Than Show Error */
            if ($transaction_type == '1' && $is_transfer == '1') {
                $response = [
                    'status'    => '400',
                    'message'   => 'Please Select Valid Transaction Type For Transfer!!!'
                ];
                return json_encode($response);
            }

            /* Chek Account Balance */
            $Balance = $this->ChekBalance($account_id);

            /* Transaction Type is Income Or Transaction Type is Expense and Balance is Big than Ammount */
            if ($transaction_type == '1' || ($transaction_type == '0' && $Balance >= $amount)) {

                // store the data
                $add = Transaction::create([
                    'account_id'        => $account_id,
                    'transaction_type'  => $transaction_type,
                    'amount'            => $amount,
                    'description'       => $description,
                    'is_transfer'       => $is_transfer,
                    'receiver_id'       => $receiver,
                    'transaction_by'    => auth()->id()
                ]);
                $transaction_id = $add->id;

                /* Call Private Function For Add Transaction Category into Pivot Table */
                $addTransactionCategory = $this->AddTransactionCategory($request, $transaction_id);

                /* Call Private Function For Account Balance Calculation */
                $AccountBalanceCalculation = $this->AccountBalanceCalculation($account_id);
            } else {
                $response = [
                    'status'    => '400',
                    'message'   => 'Not Enough Balance!!!'
                ];
                return json_encode($response);
            }
            $save = true;
        }

        /* If Save Flag Is True Than Return 200 Otherwise 400  */
        if ($save) {
            $response = [
                'status'    => '200',
                'message'   => 'Transaction Added SuccessFully!!!'
            ];
        } else {
            $response = [
                'status'    => '400',
                'message'   => 'Transaction Added Failed!!!'
            ];
        }
        return json_encode($response);
    }

    /* Edit Transaction Into Database */
    public function transactionEdit(Request $request)
    {

        $save = false; //flag Variable for Store the data into Database

        // Validate Data
        $request->validate([
            'edit_transaction_id'   => 'required|integer',
            'account_id'            => 'required|integer',
            'amount'                => 'required|numeric',
            'transaction_type'      => 'required',
        ]);

        /* Three Flag Variable For Store, Update, Create and Delete */
        $updateTransaction = false;
        $CreateOrUpdateSecondTransaction = false;
        $DeleteSecondTransaction = false;


        $edit_transaction_id = $request->edit_transaction_id;
        $account_id = $request->account_id;
        $newAmount = $request->amount;
        $transaction_type = $request->transaction_type;
        $description = $request->description;
        $is_transfer =  '0';

        if ($request->is_transfer != "") {
            $is_transfer =  $request->is_transfer;
        }
        $receiver = $is_transfer == '1' ? $request->receiver : null;

        /* Chek Account Balance */
        $totalAccountBalace = $this->ChekBalance($account_id);

        /* Get Second Transaction Data That Store For Transfer */
        $GetSecondTransaction =  $this->GetSecondTransaction($edit_transaction_id);

        /* Get Current Transaction Data */
        $getTransaction = Transaction::findOrFail($edit_transaction_id);


        if ($getTransaction) {
            $previous_Amount = $getTransaction->amount;
            $previous_transaction_type = $getTransaction->transaction_type;
            $previous_is_transfer = $getTransaction->is_transfer;
            $previous_receiver_id = $getTransaction->receiver_id;

            /* If Transfer True And Receiver Is Not empty and Account Is Not empty  */
            if ($is_transfer == '1' && $receiver != "" && $account_id != "") {

                /* If Transaction Type Income And Transfer True */
                if ($transaction_type == '1' && $is_transfer == '1') {
                    $response = [
                        'status' => '400',
                        'message' => 'Please Select Valid Transaction Type For Transfer!!!'
                    ];
                    return json_encode($response);
                }

                /* If Transaction Type Expense And Transfer True */
                if ($is_transfer == '1' && $transaction_type == '0') {

                    /* Calculate Account Balance In Edit Time and Get New Balance Amount */
                    $editTimeAccountBalance = $this->editTimeAccountBalanceCalculation($account_id, $edit_transaction_id);

                    /* If New Account Balance is Smaller Than Actual Amount Than Throw The Error */
                    if (($editTimeAccountBalance < $newAmount)) {
                        $response = [
                            'status' => '400',
                            'message' => 'Not Enough Balance In Account!!!'
                        ];
                        return json_encode($response);
                    } else {

                        /* Make This Flag True For Update Transaction And Create Or Update Second Transaction For Transfer */
                        $updateTransaction = true;
                        $CreateOrUpdateSecondTransaction = true;
                    }
                }
            }

            /* Transfer False And Receiver Is Empty */
            if ($is_transfer == '0' && $receiver == "") {

                /* Transaction Type Expense And Previous Transaction Type Income Or Expense */
                if (($transaction_type == '0' && $previous_transaction_type == "0") || ($transaction_type == '0' && $previous_transaction_type == "1")) {

                    /* Call Private Function For Edit Time Account Balance Calculation */
                    $editTimeAccountBalance = $this->editTimeAccountBalanceCalculation($account_id, $edit_transaction_id);

                    /*  Account Balance is Bigger than Amount than return Error otherwise Update transaction  */
                    if (($editTimeAccountBalance < $newAmount)) {
                        $response = [
                            'status'    => '400',
                            'message'   => 'Not Enough Balance In Account!!!'
                        ];
                        return json_encode($response);
                    } else {

                        /* Flag true For Update Transaction */
                        $updateTransaction = true;

                        /* Previous Transfer Is True And Previous receiver Not Empty  */
                        if ($previous_is_transfer == '1' && $previous_receiver_id != "") {

                            /* Flag true For Delete Second Transaction */
                            $DeleteSecondTransaction = true;
                        }
                    }
                } else {

                    /* Previous Transfer is True and Previous receiver Is not Empty and Transfer is false and receiver Is Empty Transaction Type Income or Transaction Type Expense */
                    if ($previous_is_transfer == "1" && $previous_receiver_id != "" && $is_transfer == '0' && $receiver == "" && ($transaction_type == '1' || $transaction_type == '0')) {

                        /* Make This Flag True For Delete Second Transaction for Transfer */
                        $DeleteSecondTransaction = true;
                    }
                    /* Make This Flag True For Update Transaction */
                    $updateTransaction = true;
                }
            }

            /* Transfer is False And Transaction Type Expense And Previous Transaction Type is Expense */
            if ($is_transfer == '0' && ($transaction_type == '0' && $previous_transaction_type == "0")) {

                /* call Private function For Calculate Edit Time Account Balance */
                $editTimeAccountBalance = $this->editTimeAccountBalanceCalculation($account_id, $edit_transaction_id);

                /* If Account Balance is Small Than Amount Than Show Error OtherWise Update Transaction */
                if (($editTimeAccountBalance < $newAmount)) {
                    $response = [
                        'status' => '400',
                        'message' => 'Not Enough Balance In Account!!!'
                    ];
                    return json_encode($response);
                } else {

                    /* Make Flag True for Update Transaction */
                    $updateTransaction = true;
                }
            }

            /* Update Transaction */
            if ($updateTransaction) {

                // Update The Data
                $update = [
                    'account_id'       => $account_id,
                    'transaction_type' => $transaction_type,
                    'amount'           => $newAmount,
                    'description'      => $description,
                    'is_transfer'      => $is_transfer,
                    'receiver_id'      => $receiver,
                    'transaction_by'   => auth()->id()
                ];

                /* Edit Transaction */
                $edit = Transaction::where('id', $edit_transaction_id)->where('transaction_by', auth()->id())->update($update);

                /* Call Private Function for Update Transaction Category */
                $updateTransactionCategory = $this->UpdateTransactionCategory($request, $edit_transaction_id);

                /* Call Private Function for Account Balance Calculation */
                $AccountBalanceCalculation = $this->AccountBalanceCalculation($account_id);

                $save = true;
            }

            /* Delete Second Transaction */
            if ($DeleteSecondTransaction) {

                /* Get Second Transaction Data */
                $GetSecondTransaction =  $this->GetSecondTransaction($edit_transaction_id);
                if ($GetSecondTransaction) {
                    foreach ($GetSecondTransaction as $key => $value) {
                        $SecondTransactionid = $value->id;
                        $SecondTransaction_account_id = $value->account_id;
                    }
                    /* Delete Second Transaction For Transfer  */
                    $secondTransaction_delete = Transaction::findOrFail($SecondTransactionid)->delete();

                    /* Call Private Function For Second Transaction Account Balance Calculation */
                    $AccountBalanceCalculation = $this->AccountBalanceCalculation($SecondTransaction_account_id);
                }
            }

            /* Update or Create Second Transaction */
            if ($CreateOrUpdateSecondTransaction) {

                /* Get Second Transaction Data */
                $GetSecondTransaction =  $this->GetSecondTransaction($edit_transaction_id);

                /* If Found Second Transaction Data than Update Otherwise add */
                if ($GetSecondTransaction) {
                    foreach ($GetSecondTransaction as $key => $value) {
                        $SecondTransactionid = $value->id;
                    }

                    /* Data For Update Second Transaction */
                    $update = [
                        'account_id'       => $receiver,
                        'transaction_type' => '1',
                        'amount'           => $newAmount,
                        'description'      => $description,
                        'is_transfer'      => $is_transfer,
                        'receiver_id'      => $account_id,
                        'transaction_by'   => auth()->id()
                    ];

                    /* Update Second Transaction */
                    $edit = Transaction::where('id', $SecondTransactionid)->where('transaction_by', auth()->id())->update($update);

                    /* Update Transaction Category For Second Transaction */
                    $updateTransactionCategory = $this->UpdateTransactionCategory($request, $SecondTransactionid);

                    /* Update Account Balance Calculation For Second Transaction User */
                    $ReceiverAccountBalanceCalculation = $this->AccountBalanceCalculation($receiver);
                } else {

                    // store the data For Receiver Side
                    $SecondTransactionAdd = Transaction::create([
                        'account_id'        => $receiver,
                        'transaction_type'  => '1',
                        'amount'            => $newAmount,
                        'description'       => "Add Transfer Amount",
                        'is_transfer'       => $is_transfer,
                        'receiver_id'       => $account_id,
                        'transaction_by'    => auth()->id()
                    ]);

                    /* Update Account Balance Calculation For Second Transaction user */
                    $SecondAccountBalanceCalculation = $this->AccountBalanceCalculation($receiver);
                }
            }
        }

        if ($save) {
            $response = [
                'status'    => '200',
                'message'   => 'Transaction Update SuccessFully!!!'
            ];
        } else {
            $response = [
                'status'    => '400',
                'message'   => 'Transaction Update Failed!!!'
            ];
        }
        return json_encode($response);
    }

    /* Delete Transaction Into Database */
    public function transactionDelete(Request $request)
    {

        /* Get Transaction  */
        $Transaction = Transaction::findOrFail($request->id);

        $id = $Transaction->id;
        $account_id = $Transaction->account_id;
        $transaction_type = $Transaction->transaction_type;
        $amount = $Transaction->amount;
        $receiver_id = $Transaction->receiver_id;
        $is_transfer = $Transaction->is_transfer;

        /* If Transaction type Is Income And Transfer Is True And Receiver not Empty Account not Empty than Return Error */
        if ($transaction_type == '1' && $is_transfer == '1' && $receiver_id != '' && $account_id != "") {
            $response = [
                'status'    => '400',
                'message'   => "You Can't Delete This Transaction!!!",
            ];
        }

        /* If Transaction type Is Expense And Transfer Is True And Receiver not Empty Account not Empty than Return Error */
        if ($transaction_type == '0' && $is_transfer == '1' && $receiver_id != '' && $account_id != "") {

            /* Get Second Transaction Data That Store For Transfer  */
            $secondTransaction = $this->GetSecondTransaction($id);

            foreach ($secondTransaction as $key => $value) {
                $secondTransaction_id = $value->id;
                $secondTransaction_amount = $value->amount;
            }

            /* If second Transaction amount Is Bigger Than amount Than Return 200 Or 400  */
            if ($secondTransaction_amount >= $amount) {

                /* Delete Actual Transaction */
                $delete = Transaction::findOrFail($id)->delete();

                /* Delete Second Transaction For Transfer */
                $secondTransaction_delete = Transaction::findOrFail($secondTransaction_id)->delete();

                /* Call Private Function For Account Balance Calculation */
                $AccountBalanceCalculation = $this->AccountBalanceCalculation($account_id);

                $response = [
                    'status'    => '200',
                    'message'   => "Delete This Transaction SuccessFully!!!",
                ];
            } else {
                $response = [
                    'status'    => '400',
                    'message'   => "You Can't Delete This Transaction!!!",
                ];
            }
        }

        /* If Transfer Is False And Receiver is null  */
        if ($is_transfer == 0 && $receiver_id == null) {

            /* Transaction Type Expense */
            if ($transaction_type == '0') {

                /* Delete The Transaction  */
                $delete = Transaction::findOrFail($id)->delete();

                /* Call Private Function For Account Balance Calculation */
                $AccountBalanceCalculation = $this->AccountBalanceCalculation($account_id);

                $response = [
                    'status'    => '200',
                    'message'   => "Delete This Transaction SuccessFully!!!",
                ];
            }

            /* Transaction Type Income */
            if ($transaction_type == '1') {

                /* Chek Account Balance If Big than Delete Otherwise Return error */
                $Balance = $this->ChekBalance($account_id);
                if ($Balance >= $amount) {

                    /* Delete The Transaction  */
                    $delete = Transaction::findOrFail($id)->delete();

                    /* Call Private Function For Account Balance Calculation */
                    $AccountBalanceCalculation = $this->AccountBalanceCalculation($account_id);
                    $response = [
                        'status'    => '200',
                        'message'   => "Delete This Transaction <SuccessFull></SuccessFull>y!!!",
                    ];
                } else {
                    $response = [
                        'status'    => '400',
                        'message'   => "You Can't Delete This Transaction!!!",
                    ];
                }
            }
        }
        return json_encode($response);
    }
    /* Return Account Balance */
    public function accountBalance(Request $request)
    {
        /* Chek Account Balance */
        $balance = $this->ChekBalance($request->id);
        if ($balance != "") {
            $response = [
                'status'    => '200',
                'message'   => "Account Balance!!!",
                'balance'   => $balance
            ];
        } else {
            $response = [
                'status'    => '400',
                'message'   => "Account Not Balance!!!"
            ];
        }
        return json_encode($response);
    }


    /*  Calculate Account Balance and Save */
    private function AccountBalanceCalculation($id)
    {
        if ($id > 0) {
            $NewBalance = 0;
            $AllTransaction = Transaction::where('account_id', $id)->where('transaction_by', auth()->id())->get();

            foreach ($AllTransaction as $key => $value) {
                $transaction_type = $value->transaction_type;
                $amount = $value->amount;

                if ($transaction_type == '1') {
                    $NewBalance += $amount;
                }
                if ($transaction_type == '0') {
                    $NewBalance -= $amount;
                }
            }

            /* Check If New Balance amount Is Not Negative  */
            if ($NewBalance >= 0) {
                $Account = Account::findOrFail($id);
                if ($Account) {
                    $Account->balance = $NewBalance;
                    $Account->save();
                }
                return true;
            } else {
                return false;
            }
        }
    }

    /* Calculate Account Balance In Edit Time and Return New Balance Amount */
    private function editTimeAccountBalanceCalculation($id, $transaction_id)
    {
        if ($id > 0) {
            $NewBalance = 0;
            $AllTransaction = Transaction::where('account_id', $id)->whereNot('id', $transaction_id)->where('transaction_by', auth()->id())->get();

            foreach ($AllTransaction as $key => $value) {
                $transaction_type = $value->transaction_type;
                $amount = $value->amount;

                if ($transaction_type == '1') {
                    $NewBalance += $amount;
                }
                if ($transaction_type == '0') {
                    $NewBalance -= $amount;
                }
            }
            return $NewBalance;
        }
    }

    /* Chek Account Balance Return Balance Amount */
    private function ChekBalance(string $id)
    {
        $account = Account::where('id', $id)->where('owner_id', auth()->id())->get();
        foreach ($account as $key => $value) {
            return $value->balance;
        }
    }

    /* Get Second Transaction Data That Store For Transfer And If Found Than Retun Data Of Second Transaction */
    private function GetSecondTransaction(string $id)
    {
        /* Get First Transaction Data That Store For Transfer */
        $firstTransaction = Transaction::findOrFail($id);
        if ($firstTransaction) {
            $account_id = $firstTransaction->account_id;
            $transaction_type = $firstTransaction->transaction_type == '1' ? '0' : '1';
            $amount = $firstTransaction->amount;
            $receiver_id = $firstTransaction->receiver_id;
            $is_transfer = $firstTransaction->is_transfer;
        }

        /* Get Secound Transaction Data That Store For Transfer */
        $secondTransaction = Transaction::where([
            ['account_id', $receiver_id],
            ['transaction_type', $transaction_type],
            ['amount', $amount],
            ['receiver_id', $account_id],
            ['is_transfer', $is_transfer],
            ['transaction_by', auth()->id()],
        ])->get();
        if (count($secondTransaction) > 0) {
            return $secondTransaction;
        } else {
            return false;
        }
    }

    /* Save the Transfer Transaction Into Database */
    private function SaveTransactionForTransfer($request)
    {
        $is_transfer = $request->is_transfer != "" ? $request->is_transfer : '0';
        $transaction_type = $request->transaction_type;
        $account_id = $request->account_id;
        $amount = $request->amount;
        $description = $request->description;
        $receiver = $request->receiver;

        /* Chek Transaction type Is Expense And Transfer Is 1 (True) */
        if ($transaction_type == '0' && $is_transfer == '1') {

            $Balance = $this->ChekBalance($account_id);

            /* Account Balance Is Bigger than Amount  */
            if ($Balance >= $amount) {

                // store the data For Sender Side
                $FirstTransactionAdd = Transaction::create([
                    'account_id'        => $account_id,
                    'transaction_type'  => $transaction_type,
                    'amount'            => $amount,
                    'description'       => $description,
                    'is_transfer'       => $is_transfer,
                    'receiver_id'       => $receiver,
                    'transaction_by'    => auth()->id()
                ]);
                $FirstTransaction_id = $FirstTransactionAdd->id;

                /* Add Category Data into Transaction Category Table  */
                $addTransactionCategory = $this->AddTransactionCategory($request, $FirstTransaction_id);

                /* Calculate Account Balance And Store Into Account For Sender */
                $FirstAccountBalanceCalculation = $this->AccountBalanceCalculation($account_id);

                // store the data For Receiver Side
                $SecondTransactionAdd = Transaction::create([
                    'account_id'        => $receiver,
                    'transaction_type'  => '1',
                    'amount'            => $amount,
                    'description'       => "Add Transfer Amount",
                    'is_transfer'       => $is_transfer,
                    'receiver_id'       => $account_id,
                    'transaction_by'    => auth()->id()
                ]);

                /* Calculate Account Balance And Store Into Account For Receiver */
                $SecondAccountBalanceCalculation = $this->AccountBalanceCalculation($receiver);
                return true;
            } else {
                return false;
            }
        }
    }

    /* Add Selected Transaction Category Data into Transaction Category (Pivot) Table  */
    private function AddTransactionCategory($request, $transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $category = $request->category;
        if ($category != null) {
            for ($i = 0; $i < count($category); $i++) {
                $transaction->categories()->attach($category[$i]);
            }
        }
    }

    /* Update Selected Transaction Category Data into Transaction Category (Pivot) Table  */
    private function UpdateTransactionCategory($request, $edit_transaction_id)
    {
        /* All The Current Transaction Category Remove From Pivot Table  */
        $transaction = Transaction::findOrFail($edit_transaction_id);
        foreach ($transaction->categories as $category) {
            $transaction->categories()->detach($category['id']);
        }

        /* Save The New Transaction Category into Pivot Table  */
        $newCategory = $request->category;
        if ($newCategory != null) {
            for ($i = 0; $i < count($newCategory); $i++) {
                $transaction->categories()->attach($newCategory[$i]);
            }
        }
    }
}
