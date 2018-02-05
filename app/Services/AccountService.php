<?php

namespace App\Services;

use App\Models\Account;
use App\Repositories\AccountRepository;

class AccountService
{
    private $accounts;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accounts = $accountRepository;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Account
     */
    public function createNewAccount(string $name, string $email)
    {
        $account = $this->accounts->createAccount($name, $email);

        return $this->accounts->getAccount($account->id);
    }

    /**
     * @param int $accountId
     * @return Account|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getAccount(int $accountId)
    {
        return $this->accounts->getAccount($accountId);
    }

    /**
     * @param int $accountId
     * @param bool $overdraft
     * @return Account|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function updateOverdraftFacility(int $accountId, bool $overdraft)
    {
        $account = $this->accounts->getAccount($accountId);

        // return early if no change in flag
        if ($account->allow_overdraft === $overdraft) {
            return $account;
        }

        if ($overdraft) {
            $account->allow_overdraft = true;

        } elseif ($account->balance < config('bank_app.minimum')) {    // don't switch off if account is in red
            throw new \Exception('Please ensure minimum balance to disable overdraft', 7001);

        } else {
            $account->allow_overdraft = false;
        }

        if (!$account->save()) {
            throw new \Exception('Failed to update overdraft facility');
        }

        return $account;
    }

    /**
     * @param int $accountId
     * @throws \Exception
     */
    public function closeBankAccount(int $accountId)
    {
        $account = $this->accounts->getAccount($accountId);

        if ($account->balance < config('bank_app.minimum')) {
            throw new \Exception('Minimum balance violated', 7004);
        }
    }
}
