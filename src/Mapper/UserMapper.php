<?php

namespace App\Mapper;

use App\Dto\UserDto;
use App\Entity\User;

class UserMapper
{
    /**
     * @param User $user
     * @return UserDto
     */
    public function userToUserDto(User $user): UserDto
    {
        return (new UserDto())->setUsername($user->getUsername())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setInterests($user->getInterests())
            ->setEmail($user->getEmail());
    }
}
