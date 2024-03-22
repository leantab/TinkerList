<?php

namespace App\Actions\Users;

use App\Data\CreateUserData;
use App\Models\User;

class CreateUserAction
{
    public function __invoke(CreateUserData $data)
    {
        $user = User::create($data);

        return $user;
    }
}