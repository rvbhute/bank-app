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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function switchOverdraft(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'overdraft' => 'required|boolean'
        ]);

        $userId = $request->input('user_id');
        $overdraft = $request->input('overdraft');

        try {
            $user = $this->accounts->updateOverdraftFacility($userId, $overdraft);

        } catch (\Exception $e) {
            if ($e->getCode() === 7001) {
                return response()->json(['message' => $e->getMessage()], 402);
            }

            throw $e;
        }

        $verb = $user->allow_overdraft ? 'enabled' : 'disabled';

        return response()->json([
            'message' => "overdraft facility {$verb}",
            'user' => $user
        ]);
    }

    public function viewBankAccount(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = $request->input('user_id');
        $userAccount = $this->accounts->getUserAccount($userId);

        return response()->json(['account' => $userAccount]);
    }

    public function closeBankAccount()
    {
        return response()->json(['message' => 'TBD']);
    }

}
