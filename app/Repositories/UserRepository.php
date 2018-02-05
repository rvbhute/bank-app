<?php

namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $name
     * @param string $email
     * @return User
     */
    public function createUser(string $name, string $email)
    {
        $newUser = $this->user->create([
            'name' => $name,
            'email' => $email
        ]);

        return $newUser;
    }
}
