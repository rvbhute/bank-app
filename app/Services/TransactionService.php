<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    private $transactions;
    private $accounts;

    public function __construct(TransactionRepository $transactionRepository, AccountRepository $accountRepository)
    {
        $this->transactions = $transactionRepository;
        $this->accounts = $accountRepository;
    }

    /**
     * @param int $accountId
     * @param float $amount
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function creditAccount(int $accountId, float $amount)
    {
        // convert amount to paise
        $amount = (int) ($amount * 100);
        $account = $this->accounts->getAccount($accountId);

        return $this->startTransaction($amount, $account);
    }

    /**
     * @param int $accountId
     * @param float $amount
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function debitAccount(int $accountId, float $amount)
    {
        // convert amount to paise, also convert it to negative
        $amount = (int) ($amount * -100);
        $account = $this->accounts->getAccount($accountId);
        $target = $account->balance + $amount;

        if ($target < config('bank_app.minimum')) {
            if ($account->allow_overdraft === false) {
                throw new \Exception('Minimum balance violated.', 7000);
            }

            if ($target + config('bank_app.overdraft') < config('bank_app.minimum')) {
                throw new \Exception('Overdraft violated.', 7002);
            }
        }

        return $this->startTransaction($amount, $account);
    }

    /**
     * @param float $amount
     * @param Account $account
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    private function startTransaction(float $amount, $account)
    {
        DB::beginTransaction();

        try {
            // load the transaction
            $txn = $this->transactions->insertTransaction($account->id, $amount, $account->balance);

            // update account balance
            $account->balance = $txn->balance;

            if (!$account->save()) {
                throw new \Exception('Failed to update account account balance.');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $txn;
    }

}
