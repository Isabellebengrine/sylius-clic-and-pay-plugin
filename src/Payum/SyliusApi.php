<<?php

declare(strict_types=1);

namespace Poliofu\SyliusClicAndPayPlugin\Payum;

final class SyliusApi
{
    /** @var string */
    private $username;

    /** @var string */
    private $key;

    public function __construct(string $username, string $key)
    {
        $this->username = $username;
        $this->key = $key;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
