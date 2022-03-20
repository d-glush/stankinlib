<?php

namespace Packages\UserService\UserEntity;

use DateTime;
use Packages\UserRepository\UserDTO\UserDTO;
use Packages\UserService\UserEntity\Role\Role;

class UserEntity {
    private ?int $id;
    private string $password;
    private string $login;
    private string $first_name;
    private string $last_name;
    private string $middle_name;
    private ?DateTime $registration_date;
    private ?DateTime $last_auth_date;
    private Role $role;

    public function __construct(UserDTO $userDTO)
    {
        $this->id = $userDTO->getId();
        $this->password = $userDTO->getPassword();
        $this->login = $userDTO->getLogin();
        $this->first_name = $userDTO->getFirstName();
        $this->last_name = $userDTO->getLastName();
        $this->middle_name = $userDTO->getMiddleName();
        $this->registration_date = $userDTO->getRegistrationDate()
            ? (new DateTime($userDTO->getRegistrationDate()))
            : null;
        $this->last_auth_date = $userDTO->getLastAuthDate()
            ? (new DateTime($userDTO->getLastAuthDate()))
            : null;
        $this->role = Role::from($userDTO->getRoleId());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getMiddleName(): string
    {
        return $this->middle_name;
    }

    public function setMiddleName(string $middle_name): self
    {
        $this->middle_name = $middle_name;
        return $this;
    }

    public function getRegistrationDate(): DateTime
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(DateTime $registration_date): self
    {
        $this->registration_date = $registration_date;
        return $this;
    }

    public function getLastAuthDate(): ?DateTime
    {
        return $this->last_auth_date;
    }

    public function setLastAuthDate(?DateTime $last_auth_date): self
    {
        $this->last_auth_date = $last_auth_date;
        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }
}