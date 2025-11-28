<?php

namespace App\Core\UseCase\Client;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use App\Core\UseCase\Client\DTO\ClientInputDto;
use App\Core\UseCase\Client\DTO\ClientOutputDto;
use App\Core\UseCase\UseCaseInterface;

class CreateClientUseCase implements UseCaseInterface
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    public function execute(mixed $input): ClientOutputDto
    {
        /** @var ClientInputDto $input */
        $client = new Client(
            id: null,
            name: $input->name,
            email: $input->email,
            phone: $input->phone,
            document: new CpfCnpj($input->document)
        );

        $createdClient = $this->repository->create($client);

        return new ClientOutputDto(
            id: $createdClient->getId(),
            name: $createdClient->getName(),
            email: $createdClient->getEmail(),
            phone: $createdClient->getPhone(),
            document: (string) $createdClient->getDocument()
        );
    }
}
