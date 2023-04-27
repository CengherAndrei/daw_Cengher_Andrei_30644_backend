<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addUser(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function getUserByUsername(string $username): ?User
    {
        try {
            return $this->userRepository->findOneByUsername($username);
        } catch (Exception) {
            return null;
        }
    }
}
