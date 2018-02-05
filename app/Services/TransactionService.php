<?php

namespace App\Services;


use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    private $transactions;
    private $users;

    public function __construct(TransactionRepository $transactionRepository, UserRepository $userRepository)
    {
        $this->transactions = $transactionRepository;
        $this->users = $userRepository;
    }

    /**
     * @param int $userId
     * @param float $amount
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function creditAccount(int $userId, float $amount)
    {
        // convert amount to paise
        $amount = (int) ($amount * 100);
        $user = $this->users->getUser($userId);

        return $this->startTransaction($amount, $user);
    }

    /**
     * @param int $userId
     * @param float $amount
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function debitAccount(int $userId, float $amount)
    {
        // convert amount to paise, also convert it to negative
        $amount = (int) ($amount * -100);
        $user = $this->users->getUser($userId);

        if ($user->balance + $amount < config('bank_app.minimum')) {
            throw new \Exception('Minimum balance violated.', 7000);
        }

        return $this->startTransaction($amount, $user);
    }

    /**
     * @param float $amount
     * @param User $user
     * @return Transaction|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    private function startTransaction(float $amount, $user)
    {
        DB::beginTransaction();

        try {
            // load the transaction
            $txn = $this->transactions->insertTransaction($user->id, $amount, $user->balance);

            // update user balance
            $user->balance = $txn->balance;

            if (!$user->save()) {
                throw new \Exception('Failed to update user account balance.');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $txn;
    }

}
