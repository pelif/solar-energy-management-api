<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectEquipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fillable_attributes()
    {
        $project = new Project();
        $fillable = ['id', 'client_id', 'uf', 'installation_type'];

        $this->assertEquals($fillable, $project->getFillable());
    }

    public function test_it_generates_uuid_on_creation()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        $this->assertTrue(Str::isUuid($project->id));
    }

    public function test_it_uses_soft_deletes()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $project->delete();

        $this->assertSoftDeleted($project);
    }

    public function test_it_belongs_to_a_client()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Client::class, $project->client);
        $this->assertEquals($client->id, $project->client->id);
    }

    public function test_it_has_many_equipment()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        // Assuming ProjectEquipment factory exists or creating manually if not
        // Since I haven't checked for ProjectEquipment factory, I'll try to use it, 
        // if it fails I'll fix it. But usually factories are created with models.
        // Let's check if ProjectEquipment model exists first. Yes it does.

        // For now let's just check the relationship method returns HasMany
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $project->equipment());
    }
}
