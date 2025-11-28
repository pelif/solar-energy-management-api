<?php

namespace Tests\Unit\Core\UseCase\Client;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use App\Core\UseCase\Client\CreateClientUseCase;
use App\Core\UseCase\Client\DeleteClientUseCase;
use App\Core\UseCase\Client\DTO\ClientInputDto;
use App\Core\UseCase\Client\GetClientUseCase;
use App\Core\UseCase\Client\ListClientsUseCase;
use App\Core\UseCase\Client\UpdateClientUseCase;
use Mockery;
use Tests\TestCase;

class ClientUseCasesTest extends TestCase
{
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(ClientRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_client_use_case()
    {
        $input = new ClientInputDto(
            name: 'Test Client',
            email: 'test@example.com',
            phone: '123456789',
            document: '52998224725'
        );

        $clientEntity = new Client(
            id: 'uuid',
            name: $input->name,
            email: $input->email,
            phone: $input->phone,
            document: new CpfCnpj($input->document)
        );

        $this->repository->shouldReceive('create')
            ->once()
            ->andReturn($clientEntity);

        $useCase = new CreateClientUseCase($this->repository);
        $output = $useCase->execute($input);

        $this->assertEquals('Test Client', $output->name);
        $this->assertEquals('test@example.com', $output->email);
    }

    public function test_update_client_use_case()
    {
        $input = new ClientInputDto(
            name: 'Updated Name',
            email: 'updated@example.com',
            phone: '987654321',
            document: '52998224725'
        );
        $input->id = 'uuid';

        $clientEntity = new Client(
            id: 'uuid',
            name: $input->name,
            email: $input->email,
            phone: $input->phone,
            document: new CpfCnpj($input->document)
        );

        $this->repository->shouldReceive('findById')
            ->once()
            ->with('uuid')
            ->andReturn($clientEntity);

        $this->repository->shouldReceive('update')
            ->once()
            ->andReturn($clientEntity);

        $useCase = new UpdateClientUseCase($this->repository);
        $output = $useCase->execute($input);

        $this->assertEquals('Updated Name', $output->name);
    }

    public function test_delete_client_use_case()
    {
        $this->repository->shouldReceive('delete')
            ->once()
            ->with('uuid')
            ->andReturn(true);

        $useCase = new DeleteClientUseCase($this->repository);
        $output = $useCase->execute('uuid');

        $this->assertTrue($output);
    }

    public function test_get_client_use_case()
    {
        $clientEntity = new Client(
            id: 'uuid',
            name: 'Test Client',
            email: 'test@example.com',
            phone: '123456789',
            document: new CpfCnpj('52998224725')
        );

        $this->repository->shouldReceive('findById')
            ->once()
            ->with('uuid')
            ->andReturn($clientEntity);

        $useCase = new GetClientUseCase($this->repository);
        $output = $useCase->execute('uuid');

        $this->assertEquals('Test Client', $output->name);
    }

    public function test_list_clients_use_case()
    {
        $clientEntity = new Client(
            id: 'uuid',
            name: 'Test Client',
            email: 'test@example.com',
            phone: '123456789',
            document: new CpfCnpj('52998224725')
        );

        $this->repository->shouldReceive('findAll')
            ->once()
            ->andReturn([$clientEntity]);

        $useCase = new ListClientsUseCase($this->repository);
        $output = $useCase->execute([]);

        $this->assertCount(1, $output);
        $this->assertEquals('Test Client', $output[0]->name);
    }
}
