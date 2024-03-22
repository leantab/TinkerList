<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,
        #[Email]
        public string $email,
        public string $password,
    ) {}
}