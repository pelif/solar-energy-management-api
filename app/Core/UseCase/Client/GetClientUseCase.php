<?php

namespace App\Core\UseCase\Client;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\UseCase\Client\DTO\ClientOutputDto;
use App\Core\UseCase\UseCaseInterface;
use Exception;

class GetClientUseCase implements UseCaseInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    public function execute(mixed $input): ClientOutputDto
    {
        // Input is just the ID string
        $client = $this->repository->findById((string) $input);

        if (!$client) {
            throw new Exception("Client not found");
        }

        return new ClientOutputDto(
            id: $client->getId(),
            name: $client->getName(),
            email: $client->getEmail(),
            phone: $client->getPhone(),
            document: (string) $client->getDocument()
        );
    }
}
