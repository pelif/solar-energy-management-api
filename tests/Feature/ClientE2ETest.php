<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientE2ETest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_create_client_endpoint()
    {
        $payload = [
            'name' => 'Test Client',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'document' => '52998224725' // Valid CPF
        ];

        $response = $this->postJson('/api/clients', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test Client']);

        $this->assertDatabaseHas('clients', ['email' => 'test@example.com']);
    }

    public function test_list_clients_endpoint()
    {
        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_get_client_endpoint()
    {
        $client = Client::factory()->create();

        $response = $this->getJson("/api/clients/{$client->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $client->id]);
    }

    public function test_update_client_endpoint()
    {
        $client = Client::factory()->create();
        $payload = [
            'name' => 'Updated Name',
            'email' => $client->email,
            'phone' => $client->phone,
            'document' => $client->document
        ];

        $response = $this->putJson("/api/clients/{$client->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated Name']);
    }

    public function test_delete_client_endpoint()
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
