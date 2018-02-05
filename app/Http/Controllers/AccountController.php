<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
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
            'email' => 'required|email|unique:accounts|max:255'
        ]);

        $data = $request->only(['name', 'email']);
        $account = $this->accounts->createNewAccount($data['name'], $data['email']);

        return response()->json([
            'message' => 'new account created',
            'account' => $account
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function switchOverdraftFlag(Request $request)
    {
        $this->validate($request, [
            'account_id' => 'required|exists:accounts,id',
            'overdraft' => 'required|boolean'
        ]);

        $accountId = $request->input('account_id');
        $overdraft = $request->input('overdraft');

        try {
            $account = $this->accounts->updateOverdraftFacility($accountId, $overdraft);

        } catch (\Exception $e) {
            if ($e->getCode() === 7001) {
                return response()->json(['message' => $e->getMessage()], 402);
            }

            throw $e;
        }

        $verb = $account->allow_overdraft ? 'enabled' : 'disabled';

        return response()->json([
            'message' => "overdraft facility {$verb}",
            'account' => $account
        ]);
    }

    public function viewBankAccount(Request $request)
    {
        $this->validate($request, [
            'account_id' => 'required|exists:accounts,id'
        ]);

        $accountId = $request->input('account_id');
        $account = $this->accounts->getAccount($accountId);

        return response()->json(['account' => $account]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function closeBankAccount(Request $request)
    {
        $this->validate($request, [
            'account_id' => 'required|exists:accounts,id'
        ]);

        $accountId = $request->input('account_id');

        try {
            $account = $this->accounts->closeBankAccount($accountId);

        } catch (\Exception $e) {
            if ($e->getCode() === 7004) {
                return response()->json(['message' => $e->getMessage()], 402);
            }

            throw $e;
        }

        return response()->json([
            'message' => 'account closed',
            'account' => $account
        ]);
    }

}
