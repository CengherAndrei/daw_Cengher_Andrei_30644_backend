<?php

namespace App\Dto;

class UserDto
{
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $username = null;
    private array $interests = [];
    private ?string $email = null;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return UserDto
     */
    public function setFirstName(?string $firstName): UserDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return UserDto
     */
    public function setLastName(?string $lastName): UserDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return UserDto
     */
    public function setUsername(?string $username): UserDto
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return array
     */
    public function getInterests(): array
    {
        return $this->interests;
    }

    /**
     * @param array $interests
     * @return UserDto
     */
    public function setInterests(array $interests): UserDto
    {
        $this->interests = $interests;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserDto
     */
    public function setEmail(?string $email): UserDto
    {
        $this->email = $email;
        return $this;
    }
}
