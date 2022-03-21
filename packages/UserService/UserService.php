<?php

namespace Packages\UserService;

use Packages\UserRepository\UserDTO\UserDTO;
use Packages\UserRepository\UserRepository;
use Packages\UserService\UserEntity\UserEntity;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserByLogin(string $login): UserEntity|bool
    {
        $userDTO = $this->userRepository->getByLogin($login);
        return $userDTO ? (new UserEntity($userDTO)) : false;
    }

    public function getUserById(int $id): UserEntity|bool
    {
        $userDTO = $this->userRepository->getById($id);
        return $userDTO ? (new UserEntity($userDTO)) : false;
    }

    public function getByEmail(string $email): UserEntity|bool
    {
        $userDTO = $this->userRepository->getByEmail($email);
        return $userDTO ? (new UserEntity($userDTO)) : false;
    }
}