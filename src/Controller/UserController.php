<?php

namespace App\Controller;

use App\Entity\User;
use App\Mapper\UserMapper;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;
    private UserPasswordHasherInterface $passwordHasher;
    private UserMapper $mapper;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService, UserPasswordHasherInterface $passwordHasher, UserMapper $mapper)
    {
        $this->userService = $userService;
        $this->passwordHasher = $passwordHasher;
        $this->mapper = $mapper;
    }

    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, JWTTokenManagerInterface $manager): JsonResponse
    {
        $credentials = $request->toArray();

        try {
            $username = $credentials['username'] ?? throw new \Exception('Missing parameters');
            $password = $credentials['password'] ?? throw new \Exception('Missing parameters');

            $user = $this->userService->getUserByUsername($username);

            if ($user && $this->passwordHasher->isPasswordValid($user, $password)) {
                return $this->json([
                    'token' => $manager->create($user)
                ]);
            }

            return $this->json([
                'message' => 'Unauthorized'
            ])->setStatusCode(401);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $params = $request->toArray();
        $username = $params['username'] ?? null;
        $firstname = $params['firstname'] ?? null;
        $lastname = $params['lastname'] ?? null;
        $password = $params['password'] ?? null;
        $role = $params['role'] ?? ['client'];
        $interests = $params['interests'] ?? [];
        $email = $params['email'] ?? null;

        try {
            if (!($username || $firstname || $lastname || $password || $email)) {
                throw new \Exception('Missing parameters');
            }

            if ($this->userService->getUserByUsername($username)) {
                throw new \Exception('Username already used.');
            }

            $user = (new User())->setUsername($username)->setFirstName($firstname)->setLastName($lastname)->setRoles($role)->setInterests($interests)->setEmail($email);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $this->userService->addUser($user);

            return $this->json([
                'message' => 'User successfully added.'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }

    #[Route('/api/user', name: 'app_get_user', methods: ['GET'])]
    public function getUserByUsername(Request $request): JsonResponse
    {
        $username = $request->get("username");
        $user = $this->userService->getUserByUsername($username);
        $userDto = $this->mapper->userToUserDto($user);

        return $this->json([
            'user' => $user
        ]);
    }

    #[Route('/api/user', name: 'app_update_user', methods: ['PUT'])]
    public function updateUser(Request $request): JsonResponse
    {
        $params = $request->toArray();
        $username = $params['username'];
        $firstname = $params['firstname'] ?? null;
        $lastname = $params['lastname'] ?? null;
        $email = $params['email'] ?? null;
        $interests = $params['interests'] ?? null;

        if(!$username && !$firstname && !$lastname && !$email && !$interests) {
            return $this->json([
                'message' => 'Nothing to update'
            ])->setStatusCode(400);
        }

        $user = $this->userService->getUserByUsername($username);

        if(!$user) {
            return $this->json([
                'message' => 'No user with this username'
            ])->setStatusCode(400);
        }

        if($firstname) $user->setFirstName($firstname);
        if($lastname) $user->setLastName($lastname);
        if($email) $user->setEmail($email);
        if($interests) $user->setInterests($interests);

        $this->userService->addUser($user);

        return $this->json([
            'message' => 'User successfully updated.'
        ]);
    }
}
