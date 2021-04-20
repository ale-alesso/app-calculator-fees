<?php

namespace App\Validator;

use App\Entity\User;
use App\Exception\InvalidUserException;

class UserValidator implements UserValidatorInterface
{
    /**
     * @param User $user
     * @throws InvalidUserException
     */
    public function validate(User $user): void
    {
	if ($user->getType() === null) {
	    throw new InvalidUserException('User type is not defined');
	}

	if (!in_array($user->getType(), User::$types, true)) {
	    throw new InvalidUserException('Unknown user type: ' . $user->getType());
	}
    }
}
