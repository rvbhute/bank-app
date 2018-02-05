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

    /**
     * @param int $userId
     * @param bool $overdraft
     * @return User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function updateOverdraftFacility(int $userId, bool $overdraft)
    {
        $user = $this->users->getUser($userId);

        // return early if no change in flag
        if ($user->allow_overdraft === $overdraft) {
            return $user;
        }

        if ($overdraft) {
            $user->allow_overdraft = true;

        } elseif ($user->balance < config('bank_app.minimum')) {    // don't switch off if account is in red
            throw new \Exception('Please ensure minimum balance to disable overdraft', 7001);

        } else {
            $user->allow_overdraft = false;
        }

        if (!$user->save()) {
            throw new \Exception('Failed to update overdraft facility');
        }

        return $user;
    }
}
