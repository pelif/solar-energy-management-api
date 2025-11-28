<?php

namespace App\Core\UseCase\Project\DTO;

class EquipmentDto
{
    public function __construct(
        public readonly string $type,
        public readonly int $quantity
    ) {}
}
