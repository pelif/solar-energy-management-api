<?php

namespace App\Core\UseCase\Client\DTO;

class ClientInputDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $document,
        public ?string $id = null
    ) {}
}
