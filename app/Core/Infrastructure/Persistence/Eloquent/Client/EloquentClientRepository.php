<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Client;

use App\Core\Domain\Client\Entities\Client as ClientEntity;
use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use App\Models\Client as ClientModel;

class EloquentClientRepository implements ClientRepositoryInterface
{
    public function create(ClientEntity $client): ClientEntity
    {
        $model = ClientModel::create([
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
            'document' => (string) $client->getDocument(),
        ]);

        return $this->toEntity($model);
    }

    public function update(ClientEntity $client): ClientEntity
    {
        $model = ClientModel::findOrFail($client->getId());
        $model->update([
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
            // Document is usually not updated, but if needed:
            // 'document' => (string) $client->getDocument(),
        ]);

        return $this->toEntity($model);
    }

    public function delete(string $id): bool
    {
        return (bool) ClientModel::destroy($id);
    }

    public function findById(string $id): ?ClientEntity
    {
        $model = ClientModel::find($id);
        if (!$model) {
            return null;
        }
        return $this->toEntity($model);
    }

    public function findAll(array $filters = []): array
    {
        $query = ClientModel::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['document'])) {
            $query->where('document', 'like', '%' . $filters['document'] . '%');
        }

        $models = $query->get();

        return $models->map(fn($model) => $this->toEntity($model))->toArray();
    }

    public function findByEmail(string $email): ?ClientEntity
    {
        $model = ClientModel::where('email', $email)->first();
        if (!$model) {
            return null;
        }
        return $this->toEntity($model);
    }

    public function findByDocument(string $document): ?ClientEntity
    {
        $model = ClientModel::where('document', $document)->first();
        if (!$model) {
            return null;
        }
        return $this->toEntity($model);
    }

    private function toEntity(ClientModel $model): ClientEntity
    {
        return new ClientEntity(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            phone: $model->phone,
            document: new CpfCnpj($model->document)
        );
    }
}
