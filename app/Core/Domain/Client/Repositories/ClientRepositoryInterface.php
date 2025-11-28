<?php

namespace App\Core\Domain\Client\Repositories;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\RepositoryInterface;

interface ClientRepositoryInterface extends RepositoryInterface
{
    public function create(Client $client): Client;
    public function update(Client $client): Client;
    public function delete(string $id): bool;
    public function findById(string $id): ?Client;
    /**
     * @return Client[]
     */
    public function findAll(array $filters = []): array;
    public function findByEmail(string $email): ?Client;
    public function findByDocument(string $document): ?Client;
}
