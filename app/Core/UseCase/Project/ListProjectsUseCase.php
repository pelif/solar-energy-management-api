<?php

namespace App\Core\UseCase\Project;

use App\Core\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Core\UseCase\Project\DTO\EquipmentDto;
use App\Core\UseCase\Project\DTO\ProjectOutputDto;
use App\Core\UseCase\UseCaseInterface;

class ListProjectsUseCase implements UseCaseInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository
    ) {}

    public function execute(mixed $input = null): array
    {
        $projects = $this->repository->findAll((array) $input);

        return array_map(function ($project) {
            return new ProjectOutputDto(
                id: $project->getId(),
                clientId: $project->getClientId(),
                uf: $project->getUf()->value,
                installationType: $project->getInstallationType()->value,
                equipment: array_map(fn($eq) => new EquipmentDto(
                    type: $eq->getType()->value,
                    quantity: $eq->getQuantity()
                ), $project->getEquipment())
            );
        }, $projects);
    }
}
