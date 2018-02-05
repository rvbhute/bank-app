<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param int $userId
     * @param int $amount
     * @param int $balance
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function addCreditTransaction(int $userId, int $amount, int $balance)
    {
        $txn = $this->transaction->create([
            'user_id' => $userId,
            'credit' => $amount,
            'debit' => 0,
            'balance' => $balance + $amount
        ]);

        if ($txn === false) {
            throw new \Exception('Failed to save transaction.');
        }

        return $txn;
    }

}
