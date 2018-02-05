<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AccountService
{
    private $users;

    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    /**
     * @param string $name
     * @param string $email
     * @return User
     */
    public function createNewAccount(string $name, string $email)
    {
        $account = $this->users->createUser($name, $email);

        return $account;
    }
}
