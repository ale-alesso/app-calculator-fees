<?php

namespace App\Validator;

use App\Entity\User;

interface UserValidatorInterface
{
    public function validate(User $user): void;
}
