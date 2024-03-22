<?php

namespace App\Services;

use App\Actions\Users\CreateUserAction;
use App\Data\CreateUserData;
use App\Models\User;

class GetOrCreateUserService
{
    public function __construct(
        private CreateUserAction $createUserAction
    )
    {
    }

    public function __invoke(string $email): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = $this->createUser($email);
        }

        return $user;
    }

    protected function createUser(string $email): User
    {
        $dto = CreateUserData::from([
            'name' => $email,
            'email' => $email,
            'password' => 'password',
        ]);

        $user = $this->createUserAction->__invoke($dto);

        return $user;
    }
}