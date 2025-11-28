<?php

namespace App\Core\Domain\Project\Entities;

use App\Core\Domain\Project\Enums\InstallationType;
use App\Core\Domain\Project\Enums\UF;
use App\Core\Domain\Project\ValueObjects\Equipment;

class Project
{
    /**
     * @param Equipment[] $equipment
     */
    public function __construct(
        private ?string $id,
        private string $clientId,
        private UF $uf,
        private InstallationType $installationType,
        private array $equipment = []
    ) {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getUf(): UF
    {
        return $this->uf;
    }

    public function getInstallationType(): InstallationType
    {
        return $this->installationType;
    }

    /**
     * @return Equipment[]
     */
    public function getEquipment(): array
    {
        return $this->equipment;
    }

    public function update(UF $uf, InstallationType $installationType): void
    {
        $this->uf = $uf;
        $this->installationType = $installationType;
    }

    public function addEquipment(Equipment $equipment): void
    {
        $this->equipment[] = $equipment;
    }

    public function setEquipment(array $equipment): void
    {
        $this->equipment = $equipment;
    }

    public function clearEquipment(): void
    {
        $this->equipment = [];
    }
}
