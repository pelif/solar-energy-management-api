<?php

namespace App\Core\UseCase\Client;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use App\Core\UseCase\Client\DTO\ClientInputDto;
use App\Core\UseCase\Client\DTO\ClientOutputDto;
use App\Core\UseCase\UseCaseInterface;
use Exception;

class UpdateClientUseCase implements UseCaseInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    public function execute(mixed $input): ClientOutputDto
    {
        /** @var ClientInputDto $input */
        $client = $this->repository->findById($input->id);

        if (!$client) {
            throw new Exception("Client not found");
        }

        $client->update(
            $input->name,
            $input->email,
            $input->phone
        );

        // Note: Document usually isn't updated, but if needed, we'd need a setter or logic here.
        // For now, assuming document is immutable after creation or requires specific process.

        $updatedClient = $this->repository->update($client);

        return new ClientOutputDto(
            id: $updatedClient->getId(),
            name: $updatedClient->getName(),
            email: $updatedClient->getEmail(),
            phone: $updatedClient->getPhone(),
            document: (string) $updatedClient->getDocument()
        );
    }
}
