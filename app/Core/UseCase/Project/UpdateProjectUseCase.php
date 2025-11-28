<?php

namespace App\Core\UseCase\Project;

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

class UpdateProjectUseCase implements UseCaseInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository
    ) {}

    public function execute(mixed $input): ProjectOutputDto
    {
        if (!$input instanceof ProjectInputDto) {
            throw new Exception('Invalid input type');
        }

        $project = $this->repository->findById($input->id);

        if (!$project) {
            throw new Exception('Project not found');
        }

        // Update basic fields
        $uf = UF::from($input->uf);
        $installationType = InstallationType::from($input->installationType);
        $project->update($uf, $installationType);

        // Update equipment list
        $equipment = array_map(function ($equipmentDto) {
            $type = EquipmentType::from($equipmentDto->type);
            return new Equipment($type, $equipmentDto->quantity);
        }, $input->equipment);

        $project->setEquipment($equipment);

        // Persist
        $updatedProject = $this->repository->update($project);

        return new ProjectOutputDto(
            id: $updatedProject->getId(),
            clientId: $updatedProject->getClientId(),
            uf: $updatedProject->getUf()->value,
            installationType: $updatedProject->getInstallationType()->value,
            equipment: array_map(fn($eq) => new EquipmentDto(
                type: $eq->getType()->value,
                quantity: $eq->getQuantity()
            ), $updatedProject->getEquipment())
        );
    }
}
