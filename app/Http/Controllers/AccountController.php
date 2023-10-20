<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function accountsList()
    {
        return view('accounts.accountsList');
    }
    public function accountsAdd()
    {
        return view('accounts.accountsAdd');
    }
}
