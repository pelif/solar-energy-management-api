<?php

namespace App\Core\Domain\Project\Repositories;

use App\Core\Domain\Project\Entities\Project;
use App\Core\Domain\RepositoryInterface;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    public function create(Project $project): Project;
    public function update(Project $project): Project;
    public function delete(string $id): bool;
    public function findById(string $id): ?Project;
    /**
     * @return Project[]
     */
    public function findAll(array $filters = []): array;
    /**
     * @return Project[]
     */
    public function findByClientId(string $clientId): array;
}
