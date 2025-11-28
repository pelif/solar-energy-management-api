<?php

namespace Tests\Unit\Core\UseCase\Project;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use App\Core\Domain\Project\Entities\Project;
use App\Core\Domain\Project\Enums\EquipmentType;
use App\Core\Domain\Project\Enums\InstallationType;
use App\Core\Domain\Project\Enums\UF;
use App\Core\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Core\Domain\Project\ValueObjects\Equipment;
use App\Core\UseCase\Project\CreateProjectUseCase;
use App\Core\UseCase\Project\DeleteProjectUseCase;
use App\Core\UseCase\Project\DTO\EquipmentDto;
use App\Core\UseCase\Project\DTO\ProjectInputDto;
use App\Core\UseCase\Project\GetProjectUseCase;
use App\Core\UseCase\Project\ListProjectsUseCase;
use App\Core\UseCase\Project\UpdateProjectUseCase;
use Mockery;
use Tests\TestCase;

class ProjectUseCasesTest extends TestCase
{
    private $projectRepository;
    private $clientRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectRepository = Mockery::mock(ProjectRepositoryInterface::class);
        $this->clientRepository = Mockery::mock(ClientRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_project_use_case()
    {
        $equipmentDto = new EquipmentDto(type: 'Módulo', quantity: 10);
        $input = new ProjectInputDto(
            clientId: 'client-uuid',
            uf: 'SP',
            installationType: 'Fibrocimento (Madeira)',
            equipment: [$equipmentDto]
        );

        $clientEntity = new Client(
            id: 'client-uuid',
            name: 'Test Client',
            email: 'test@example.com',
            phone: '123456789',
            document: new CpfCnpj('52998224725')
        );

        $this->clientRepository->shouldReceive('findById')
            ->once()
            ->with('client-uuid')
            ->andReturn($clientEntity);

        $projectEntity = new Project(
            id: 'project-uuid',
            clientId: 'client-uuid',
            uf: UF::SP,
            installationType: InstallationType::FIBROCIMENTO_MADEIRA,
            equipment: [new Equipment(EquipmentType::MODULO, 10)]
        );

        $this->projectRepository->shouldReceive('create')
            ->once()
            ->andReturn($projectEntity);

        $useCase = new CreateProjectUseCase($this->projectRepository, $this->clientRepository);
        $output = $useCase->execute($input);

        $this->assertEquals('project-uuid', $output->id);
        $this->assertEquals('SP', $output->uf);
    }

    public function test_update_project_use_case()
    {
        $equipmentDto = new EquipmentDto(type: 'Inversor', quantity: 2);
        $input = new ProjectInputDto(
            id: 'project-uuid',
            clientId: 'client-uuid',
            uf: 'RJ',
            installationType: 'Metálico',
            equipment: [$equipmentDto]
        );

        $projectEntity = new Project(
            id: 'project-uuid',
            clientId: 'client-uuid',
            uf: UF::RJ,
            installationType: InstallationType::METALICO,
            equipment: [new Equipment(EquipmentType::INVERSOR, 2)]
        );

        $this->projectRepository->shouldReceive('findById')
            ->once()
            ->with('project-uuid')
            ->andReturn($projectEntity);

        $this->projectRepository->shouldReceive('update')
            ->once()
            ->andReturn($projectEntity);

        $useCase = new UpdateProjectUseCase($this->projectRepository);
        $output = $useCase->execute($input);

        $this->assertEquals('RJ', $output->uf);
        $this->assertEquals('Metálico', $output->installationType);
    }

    public function test_delete_project_use_case()
    {
        $projectEntity = new Project(
            id: 'project-uuid',
            clientId: 'client-uuid',
            uf: UF::SP,
            installationType: InstallationType::FIBROCIMENTO_MADEIRA,
            equipment: []
        );

        $this->projectRepository->shouldReceive('findById')
            ->once()
            ->with('project-uuid')
            ->andReturn($projectEntity);

        $this->projectRepository->shouldReceive('delete')
            ->once()
            ->with('project-uuid')
            ->andReturn(true);

        $useCase = new DeleteProjectUseCase($this->projectRepository);
        $output = $useCase->execute('project-uuid');

        $this->assertTrue($output);
    }

    public function test_get_project_use_case()
    {
        $projectEntity = new Project(
            id: 'project-uuid',
            clientId: 'client-uuid',
            uf: UF::SP,
            installationType: InstallationType::FIBROCIMENTO_MADEIRA,
            equipment: [new Equipment(EquipmentType::MODULO, 10)]
        );

        $this->projectRepository->shouldReceive('findById')
            ->once()
            ->with('project-uuid')
            ->andReturn($projectEntity);

        $useCase = new GetProjectUseCase($this->projectRepository);
        $output = $useCase->execute('project-uuid');

        $this->assertEquals('project-uuid', $output->id);
    }

    public function test_list_projects_use_case()
    {
        $projectEntity = new Project(
            id: 'project-uuid',
            clientId: 'client-uuid',
            uf: UF::SP,
            installationType: InstallationType::FIBROCIMENTO_MADEIRA,
            equipment: [new Equipment(EquipmentType::MODULO, 10)]
        );

        $this->projectRepository->shouldReceive('findAll')
            ->once()
            ->andReturn([$projectEntity]);

        $useCase = new ListProjectsUseCase($this->projectRepository);
        $output = $useCase->execute([]);

        $this->assertCount(1, $output);
        $this->assertEquals('project-uuid', $output[0]->id);
    }
}
