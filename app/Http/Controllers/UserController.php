<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $accounts;

    public function __construct(AccountService $accountService)
    {
        $this->accounts = $accountService;
    }

    public function openBankAccount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255'
        ]);

        $data = $request->only(['name', 'email']);
        $account = $this->accounts->createNewAccount($data['name'], $data['email']);

        return response()->json([
            'message' => 'new account created',
            'account' => $account
        ]);
    }

    public function closeBankAccount()
    {
        return response()->json(['message' => 'TBD']);
    }

}
