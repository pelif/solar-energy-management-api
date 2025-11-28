<?php

namespace Tests\Unit\Core\Infrastructure\Client;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use App\Core\Infrastructure\Persistence\Eloquent\Client\EloquentClientRepository;
use App\Models\Client as ClientModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentClientRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentClientRepository();
    }

    public function test_create_client()
    {
        $clientEntity = new Client(
            id: null,
            name: 'Test Client',
            email: 'test@example.com',
            phone: '123456789',
            document: new CpfCnpj('52998224725') // Valid CPF
        );

        $createdClient = $this->repository->create($clientEntity);

        $this->assertNotNull($createdClient->getId());
        $this->assertEquals('Test Client', $createdClient->getName());
        $this->assertDatabaseHas('clients', ['email' => 'test@example.com']);
    }

    public function test_find_client_by_id()
    {
        $model = ClientModel::factory()->create();

        $foundClient = $this->repository->findById($model->id);

        $this->assertNotNull($foundClient);
        $this->assertEquals($model->id, $foundClient->getId());
    }

    public function test_update_client()
    {
        $model = ClientModel::factory()->create();
        $clientEntity = new Client(
            id: $model->id,
            name: 'Updated Name',
            email: $model->email,
            phone: $model->phone,
            document: new CpfCnpj($model->document)
        );

        $updatedClient = $this->repository->update($clientEntity);

        $this->assertEquals('Updated Name', $updatedClient->getName());
        $this->assertDatabaseHas('clients', ['id' => $model->id, 'name' => 'Updated Name']);
    }

    public function test_delete_client()
    {
        $model = ClientModel::factory()->create();

        $result = $this->repository->delete($model->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('clients', ['id' => $model->id]);
    }

    public function test_find_all_clients()
    {
        ClientModel::factory()->count(3)->create();

        $clients = $this->repository->findAll();

        $this->assertCount(3, $clients);
        $this->assertInstanceOf(Client::class, $clients[0]);
    }
}
