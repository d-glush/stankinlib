<?php

namespace Packages\CurrentUserService;

use Packages\UserService\UserEntity\Role\Role;
use Packages\UserService\UserEntity\UserEntity;

class CurrentUserService
{
    private bool $isAuthed = false;
    private ?string $login = null;
    private ?Role $role = null;

    public function __construct()
    {
        $this->getDataFromSession();
    }

    public function login(UserEntity $user) {
        $this->isAuthed = true;
        $this->login = $user->getLogin();
        $this->role = $user->getRole();
        $this->setDataToSession();
    }

    public function logout()
    {
        $this->isAuthed = false;
        $this->login = null;
        $this->role = null;
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
        $this->login = $data['login'];
        $this->role = Role::from($data['role']);
    }

    private function setDataToSession()
    {
        $_SESSION['userData'] = [
            'is_authed' => $this->isAuthed,
            'login' => $this->login,
            'role' => $this->role?->value
        ];
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function isAuthed(): bool
    {
        return $this->isAuthed;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }
}