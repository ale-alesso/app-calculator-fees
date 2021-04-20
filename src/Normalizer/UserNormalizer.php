<?php

namespace App\Normalizer;

use App\Entity\User;
use App\Exception\InvalidMappingException;

class UserNormalizer
{
    /**
     * @param array $data
     * @return User
     * @throws InvalidMappingException
     */
    public function toObject($data): User
    {
	if (!isset($data['user_id'])) {
	    throw new InvalidMappingException('User id is not set');
	}

	if (!isset($data['user_type'])) {
	    throw new InvalidMappingException('User type is not set');
	}

	return new User((int)$data['user_id'], $data['user_type']);
    }
}
