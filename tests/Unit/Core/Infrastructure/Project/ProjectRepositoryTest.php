<?php

namespace Tests\Unit\Core\Infrastructure\Project;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\Project\Entities\Project;
use App\Core\Domain\Project\Enums\EquipmentType;
use App\Core\Domain\Project\Enums\InstallationType;
use App\Core\Domain\Project\Enums\UF;
use App\Core\Domain\Project\ValueObjects\Equipment;
use App\Core\Infrastructure\Persistence\Eloquent\Project\EloquentProjectRepository;
use App\Models\Client as ClientModel;
use App\Models\Project as ProjectModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentProjectRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentProjectRepository();
    }

    public function test_create_project()
    {
        $client = ClientModel::factory()->create();
        $equipment = new Equipment(EquipmentType::MODULO, 10);
        $projectEntity = new Project(
            id: null,
            clientId: $client->id,
            uf: UF::SP,
            installationType: InstallationType::FIBROCIMENTO_MADEIRA,
            equipment: [$equipment]
        );

        $createdProject = $this->repository->create($projectEntity);

        $this->assertNotNull($createdProject->getId());
        $this->assertEquals($client->id, $createdProject->getClientId());
        $this->assertDatabaseHas('projects', ['client_id' => $client->id]);
        $this->assertDatabaseHas('project_equipment', ['quantity' => 10]);
    }

    public function test_find_project_by_id()
    {
        $client = ClientModel::factory()->create();
        $projectModel = ProjectModel::factory()->create(['client_id' => $client->id]);

        $foundProject = $this->repository->findById($projectModel->id);

        $this->assertNotNull($foundProject);
        $this->assertEquals($projectModel->id, $foundProject->getId());
    }

    public function test_update_project()
    {
        $client = ClientModel::factory()->create();
        $projectModel = ProjectModel::factory()->create(['client_id' => $client->id]);

        $equipment = new Equipment(EquipmentType::INVERSOR, 2);
        $projectEntity = new Project(
            id: $projectModel->id,
            clientId: $client->id,
            uf: UF::RJ,
            installationType: InstallationType::METALICO,
            equipment: [$equipment]
        );

        $updatedProject = $this->repository->update($projectEntity);

        $this->assertEquals(UF::RJ, $updatedProject->getUf());
        $this->assertDatabaseHas('projects', ['id' => $projectModel->id, 'uf' => 'RJ']);
        $this->assertDatabaseHas('project_equipment', ['project_id' => $projectModel->id, 'equipment_type' => 'Inversor', 'quantity' => 2]);
    }

    public function test_delete_project()
    {
        $client = ClientModel::factory()->create();
        $projectModel = ProjectModel::factory()->create(['client_id' => $client->id]);

        $result = $this->repository->delete($projectModel->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('projects', ['id' => $projectModel->id]);
    }

    public function test_find_all_projects()
    {
        $client = ClientModel::factory()->create();
        ProjectModel::factory()->count(3)->create(['client_id' => $client->id]);

        $projects = $this->repository->findAll();

        $this->assertCount(3, $projects);
        $this->assertInstanceOf(Project::class, $projects[0]);
    }
}
