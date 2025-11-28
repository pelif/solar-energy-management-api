<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClientModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_correct_fillable_attributes()
    {
        $client = new Client();
        $fillable = ['id', 'name', 'email', 'phone', 'document'];

        $this->assertEquals($fillable, $client->getFillable());
    }

    public function test_it_generates_uuid_on_creation()
    {
        $client = Client::factory()->create();

        $this->assertTrue(Str::isUuid($client->id));
    }

    public function test_it_uses_soft_deletes()
    {
        $client = Client::factory()->create();
        $client->delete();

        $this->assertSoftDeleted($client);
    }

    public function test_it_has_string_key_type_and_not_incrementing()
    {
        $client = new Client();

        $this->assertEquals('string', $client->getKeyType());
        $this->assertFalse($client->getIncrementing());
    }
}
