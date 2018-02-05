<?php

namespace App\Http\Controllers;


use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transactions;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactions = $transactionService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function creditAccount(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|nonzero'
        ]);

        $userId = $request->input('user_id');
        $amount = $request->input('amount');

        $txn = $this->transactions->creditAccount($userId, $amount);

        return response()->json([
            'message' => 'deposit successful',
            'transaction' => $txn
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function debitAccount(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|nonzero'
        ]);

        $userId = $request->input('user_id');
        $amount = $request->input('amount');

        try {
            $txn = $this->transactions->debitAccount($userId, $amount);

        } catch (\Exception $e) {
            if ($e->getCode() === 7000) {
                return response()->json(['message' => $e->getMessage()], 402);
            }

            throw $e;
        }

        return response()->json([
            'message' => 'withdrawal successful',
            'transaction' => $txn
        ]);
    }

    public function getBalanceStatement()
    {

    }
}
