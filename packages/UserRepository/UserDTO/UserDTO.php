<?php

namespace Packages\UserRepository\UserDTO;

use Packages\DTO\DTO;

class UserDTO extends DTO
{
    private ?int $id;
    private string $password;
    private string $login;
    private string $first_name;
    private string $last_name;
    private string $middle_name;
    private ?string $registration_date;
    private ?string $last_auth_date;
    private int $role_id;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->password = $data['password'];
        $this->login = $data['login'];
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->middle_name = $data['middle_name'];
        $this->registration_date = $data['registration_date'] ?? null;
        $this->last_auth_date = $data['last_auth_date'] ?? null;
        $this->role_id = $data['role_id'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getMiddleName(): string
    {
        return $this->middle_name;
    }

    public function getRegistrationDate(): ?string
    {
        return $this->registration_date;
    }

    public function getLastAuthDate(): ?string
    {
        return $this->last_auth_date;
    }

    public function getRoleId(): int
    {
        return $this->role_id;
    }
}