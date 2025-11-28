<?php

namespace App\Core\UseCase\Project\DTO;

class ProjectInputDto
{
    /**
     * @param EquipmentDto[] $equipment
     */
    public function __construct(
        public readonly string $clientId,
        public readonly string $uf,
        public readonly string $installationType,
        public readonly array $equipment = [],
        public readonly ?string $id = null
    ) {}
}
