<?php

namespace Tests\Unit\Core\Domain\Client\Entities;

use App\Core\Domain\Client\Entities\Client;
use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function test_it_creates_client()
    {
        $document = new CpfCnpj('52998224725');
        $client = new Client(
            id: '123',
            name: 'John Doe',
            email: 'john@example.com',
            phone: '123456789',
            document: $document
        );

        $this->assertEquals('123', $client->getId());
        $this->assertEquals('John Doe', $client->getName());
        $this->assertEquals('john@example.com', $client->getEmail());
        $this->assertEquals('123456789', $client->getPhone());
        $this->assertSame($document, $client->getDocument());
    }

    public function test_it_updates_client()
    {
        $document = new CpfCnpj('52998224725');
        $client = new Client(
            id: '123',
            name: 'John Doe',
            email: 'john@example.com',
            phone: '123456789',
            document: $document
        );

        $client->update('Jane Doe', 'jane@example.com', '987654321');

        $this->assertEquals('Jane Doe', $client->getName());
        $this->assertEquals('jane@example.com', $client->getEmail());
        $this->assertEquals('987654321', $client->getPhone());
    }
}
