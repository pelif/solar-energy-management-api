<?php

namespace App\Core\UseCase\Project\DTO;

class ProjectOutputDto
{
    /**
     * @param EquipmentDto[] $equipment
     */
    public function __construct(
        public readonly string $id,
        public readonly string $clientId,
        public readonly string $uf,
        public readonly string $installationType,
        public readonly array $equipment
    ) {}
}
