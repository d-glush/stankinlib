<?php

namespace Packages\CurrentUserService;

use Packages\UserService\UserEntity\UserEntity;
use Packages\UserService\UserService;

class CurrentUserService
{
    private UserService $userService;
    private bool $isAuthed = false;
    private ?UserEntity $user = null;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->getDataFromSession();
    }

    public function login(UserEntity $user) {
        $this->isAuthed = true;
        $this->user = $user;
        $this->setDataToSession();
    }

    public function logout()
    {
        $this->isAuthed = false;
        $this->user = null;
        $this->setDataToSession();
    }

    private function getDataFromSession()
    {
        if (
            !isset($_SESSION['userData'])
            || !isset($_SESSION['userData']['is_authed'])
            || $_SESSION['userData']['is_authed'] === false
        ) {
            $this->isAuthed = false;
            return;
        }
        $this->isAuthed = true;
        $data = $_SESSION['userData'];
        $this->user = $this->userService->getUserById($data['id']);
    }

    private function setDataToSession()
    {
        $_SESSION['userData'] = [
            'is_authed' => $this->isAuthed,
            'id' => $this->isAuthed ? $this->user->getId() : null,
        ];
    }

    public function isAuthed(): bool
    {
        return $this->isAuthed;
    }

    public function getUser(): ?UserEntity
    {
        return $this->user;
    }
}