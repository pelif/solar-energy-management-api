<?php

namespace App\Core\UseCase\Project;

use App\Core\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Core\UseCase\UseCaseInterface;
use Exception;

class DeleteProjectUseCase implements UseCaseInterface
{
    public function __construct(
        private ProjectRepositoryInterface $repository
    ) {}

    public function execute(mixed $input): bool
    {
        $project = $this->repository->findById($input);

        if (!$project) {
            throw new Exception('Project not found');
        }

        return $this->repository->delete($input);
    }
}
