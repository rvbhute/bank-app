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
     * @param int $accountId
     * @param int $amount
     * @param int $balance
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function insertTransaction(int $accountId, int $amount, int $balance)
    {
        if ($amount > 0) {
            $credit = $amount;
            $debit = 0;

        } else {
            $credit = 0;
            $debit = $amount * -1; // we are storing all values as positive, sign is only for calculation
        }

        $txn = $this->transaction->create([
            'account_id' => $accountId,
            'credit' => $credit,
            'debit' => $debit,
            'balance' => $balance + $amount
        ]);

        if ($txn === false) {
            throw new \Exception('Failed to save transaction.');
        }

        return $txn;
    }

}
