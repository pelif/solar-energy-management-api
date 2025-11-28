<?php

namespace App\Core\UseCase\Client\DTO;

class ClientOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $phone,
        public string $document
    ) {}
}
