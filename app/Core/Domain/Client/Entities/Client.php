<?php

namespace App\Core\Domain\Client\Entities;

use App\Core\Domain\Client\ValueObjects\CpfCnpj;

class Client
{
    public function __construct(
        private ?string $id,
        private string $name,
        private string $email,
        private string $phone,
        private CpfCnpj $document
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getDocument(): CpfCnpj
    {
        return $this->document;
    }

    public function update(string $name, string $email, string $phone): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
}
