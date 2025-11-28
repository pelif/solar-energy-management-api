<?php

namespace App\Core\UseCase\Client;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\UseCase\Client\DTO\ClientOutputDto;
use App\Core\UseCase\UseCaseInterface;

class ListClientsUseCase implements UseCaseInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    public function execute(mixed $input = null): array
    {
        $clients = $this->repository->findAll((array) $input);

        return array_map(function ($client) {
            return new ClientOutputDto(
                id: $client->getId(),
                name: $client->getName(),
                email: $client->getEmail(),
                phone: $client->getPhone(),
                document: (string) $client->getDocument()
            );
        }, $clients);
    }
}
