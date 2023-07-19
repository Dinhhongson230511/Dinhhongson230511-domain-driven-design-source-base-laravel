<?php

namespace Project\Domain\UserManagement\User\Models;

use Project\Domain\Base\BaseDomainModel;

/**
 *
 */
class User extends BaseDomainModel
{
    private string $name;

    private string $email;

    private ?string $emailVerifiedAt;

    private string $password;

    private ?string $rememberToken;


    public function __construct(
        string $name,
        string $email,
        string $password,
        ?string $emailVerifiedAt = null,
        ?string $rememberToken = null,
    ) {
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setEmailVerifiedAt($emailVerifiedAt);
        $this->setRememberToken($rememberToken);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

        /**
     * @return string|null
     */
    public function getEmailVerifiedAt(): ?string
    {
        return $this->emailVerifiedAt;
    }

    /**
     * @param string|null $emailVerifiedAt
     */
    public function setEmailVerifiedAt(?int $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * @return string|null
     */
    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    /**
     * @param string|null $rememberToken
     */
    public function setRememberToken(?int $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }
}
