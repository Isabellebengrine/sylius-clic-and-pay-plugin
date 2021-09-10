<?php

declare(strict_types=1);

namespace Poliofu\SyliusClicAndPayPlugin\Payum;

final class SyliusApi
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $publicKey;

    /** @var string */
    private $sha256Key;

    public function __construct(string $username, string $pass, string $key, string $sha256Key)
    {
        $this->username = $username;
        $this->password = $pass;
        $this->publicKey = $key;
        $this->sha256Key = $sha256Key;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getSha256Key(): string
    {
        return $this->sha256Key;
    }
}
