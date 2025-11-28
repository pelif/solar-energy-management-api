<?php

namespace App\Core\UseCase\Client;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\UseCase\UseCaseInterface;

class DeleteClientUseCase implements UseCaseInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    public function execute(mixed $input): bool
    {
        // Input is just the ID string
        return $this->repository->delete((string) $input);
    }
}
