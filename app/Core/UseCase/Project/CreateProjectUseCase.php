<?php

namespace App\Core\UseCase\Project;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Domain\Project\Entities\Project;
use App\Core\Domain\Project\Enums\EquipmentType;
use App\Core\Domain\Project\Enums\InstallationType;
use App\Core\Domain\Project\Enums\UF;
use App\Core\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Core\Domain\Project\ValueObjects\Equipment;
use App\Core\UseCase\Project\DTO\EquipmentDto;
use App\Core\UseCase\Project\DTO\ProjectInputDto;
use App\Core\UseCase\Project\DTO\ProjectOutputDto;
use App\Core\UseCase\UseCaseInterface;
use Exception;

class CreateProjectUseCase implements UseCaseInterface
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private ClientRepositoryInterface $clientRepository
    ) {}

    public function execute(mixed $input): ProjectOutputDto
    {
        if (!$input instanceof ProjectInputDto) {
            throw new Exception('Invalid input type');
        }

        // Validate client exists
        $client = $this->clientRepository->findById($input->clientId);
        if (!$client) {
            throw new Exception('Client not found');
        }

        // Convert string to enum
        $uf = UF::from($input->uf);
        $installationType = InstallationType::from($input->installationType);

        // Convert equipment DTOs to value objects
        $equipment = array_map(function ($equipmentDto) {
            $type = EquipmentType::from($equipmentDto->type);
            return new Equipment($type, $equipmentDto->quantity);
        }, $input->equipment);

        // Create entity
        $project = new Project(
            id: null,
            clientId: $input->clientId,
            uf: $uf,
            installationType: $installationType,
            equipment: $equipment
        );

        // Persist
        $createdProject = $this->projectRepository->create($project);

        // Return DTO
        return new ProjectOutputDto(
            id: $createdProject->getId(),
            clientId: $createdProject->getClientId(),
            uf: $createdProject->getUf()->value,
            installationType: $createdProject->getInstallationType()->value,
            equipment: array_map(fn($eq) => new EquipmentDto(
                type: $eq->getType()->value,
                quantity: $eq->getQuantity()
            ), $createdProject->getEquipment())
        );
    }
}
