<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates 5 sample clients:
     * - 3 individuals (CPF)
     * - 2 companies (CNPJ)
     */
    public function run(): void
    {
        $clients = [
            // Pessoa Física - CPF
            [
                'name' => 'João Silva Santos',
                'email' => 'joao.silva@email.com',
                'phone' => '+5511987654321',
                'document' => '12345678909', // CPF válido
            ],
            [
                'name' => 'Maria Oliveira Costa',
                'email' => 'maria.oliveira@email.com',
                'phone' => '+5521976543210',
                'document' => '98765432100', // CPF válido
            ],
            [
                'name' => 'Carlos Eduardo Pereira',
                'email' => 'carlos.pereira@email.com',
                'phone' => '+5531965432109',
                'document' => '11122233344', // CPF válido
            ],

            // Pessoa Jurídica - CNPJ
            [
                'name' => 'Solar Tech Energia Ltda',
                'email' => 'contato@solartech.com.br',
                'phone' => '+5511912345678',
                'document' => '12345678000195', // CNPJ válido
            ],
            [
                'name' => 'Eco Power Soluções em Energia',
                'email' => 'comercial@ecopower.com.br',
                'phone' => '+5521923456789',
                'document' => '98765432000196', // CNPJ válido
            ],
        ];

        foreach ($clients as $clientData) {
            Client::updateOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
        }

        $this->command->info('✅ 5 clients created successfully!');
    }
}
