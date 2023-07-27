<?php

namespace Ecommerce\Domain\Model;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class User
{
    public const SESSION = 'User';
    private string $tableName = 'tb_users';

    public function __construct(
        private int $id,
        private string $login,
        private string $password,
        private bool $isAdmin,
        private DateTimeImmutable $registerDate
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    public function getRegisterDate(): DateTimeImmutable
    {
        return $this->registerDate;
    }

    public function getData(): string
    {
        return json_encode([
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'admin' => $this->isAdmin,
            'registerDate' => $this->getRegisterDate()->format('Y-m-d H:i:s')
        ]);
    }
}
