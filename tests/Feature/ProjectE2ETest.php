<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectE2ETest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_create_project_endpoint()
    {
        $client = Client::factory()->create();
        $payload = [
            'client_id' => $client->id,
            'uf' => 'SP',
            'installation_type' => 'Fibrocimento (Madeira)',
            'equipment' => [
                ['type' => 'Módulo', 'quantity' => 10]
            ]
        ];

        $response = $this->postJson('/api/projects', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['uf' => 'SP']);

        $this->assertDatabaseHas('projects', ['client_id' => $client->id]);
    }

    public function test_list_projects_endpoint()
    {
        $client = Client::factory()->create();
        Project::factory()->count(3)->create(['client_id' => $client->id]);

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_get_project_endpoint()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $project->id]);
    }

    public function test_update_project_endpoint()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        $payload = [
            'client_id' => $client->id,
            'uf' => 'RJ',
            'installation_type' => 'Metálico',
            'equipment' => [
                ['type' => 'Inversor', 'quantity' => 2]
            ]
        ];

        $response = $this->putJson("/api/projects/{$project->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['uf' => 'RJ']);

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'uf' => 'RJ']);
    }

    public function test_delete_project_endpoint()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }
}
