<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param string $name
     * @param string $email
     * @return Account
     */
    public function createAccount(string $name, string $email)
    {
        $newAccount = $this->account->create([
            'name' => $name,
            'email' => $email
        ]);

        return $newAccount;
    }

    /**
     * @param int $id
     * @return Account|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getAccount(int $id)
    {
        return $this->account->findOrFail($id);
    }

}
