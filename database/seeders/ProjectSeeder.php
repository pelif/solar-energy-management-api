<?php

namespace Database\Seeders;

use App\Core\Domain\Project\Enums\EquipmentType;
use App\Core\Domain\Project\Enums\InstallationType;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectEquipment;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates 10 sample projects with different:
     * - UFs (states)
     * - Installation types (all 6 types from Enum)
     * - Equipment combinations (using all 9 equipment types)
     */
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            $this->command->error('❌ No clients found! Run ClientSeeder first.');
            return;
        }

        $projects = [
            // Project 1 - Fibrocimento Madeira
            [
                'client' => $clients[0],
                'uf' => 'SP',
                'installation_type' => InstallationType::FIBROCIMENTO_MADEIRA->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 12],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 1],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 12],
                    ['type' => EquipmentType::CABO_VERMELHO->value, 'quantity' => 50],
                    ['type' => EquipmentType::CABO_PRETO->value, 'quantity' => 50],
                ],
            ],

            // Project 2 - Fibrocimento Metálico
            [
                'client' => $clients[0],
                'uf' => 'RJ',
                'installation_type' => InstallationType::FIBROCIMENTO_METALICO->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 20],
                    ['type' => EquipmentType::MICROINVERSOR->value, 'quantity' => 20],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 20],
                    ['type' => EquipmentType::STRING_BOX->value, 'quantity' => 1],
                ],
            ],

            // Project 3 - Cerâmico
            [
                'client' => $clients[1],
                'uf' => 'MG',
                'installation_type' => InstallationType::CERAMICO->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 16],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 1],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 16],
                    ['type' => EquipmentType::CABO_TRONCO->value, 'quantity' => 30],
                    ['type' => EquipmentType::ENDCAP->value, 'quantity' => 2],
                ],
            ],

            // Project 4 - Metálico
            [
                'client' => $clients[1],
                'uf' => 'RS',
                'installation_type' => InstallationType::METALICO->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 24],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 2],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 24],
                    ['type' => EquipmentType::STRING_BOX->value, 'quantity' => 2],
                ],
            ],

            // Project 5 - Laje
            [
                'client' => $clients[2],
                'uf' => 'BA',
                'installation_type' => InstallationType::LAJE->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 30],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 2],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 30],
                    ['type' => EquipmentType::CABO_VERMELHO->value, 'quantity' => 80],
                    ['type' => EquipmentType::CABO_PRETO->value, 'quantity' => 80],
                ],
            ],

            // Project 6 - Solo
            [
                'client' => $clients[2],
                'uf' => 'PR',
                'installation_type' => InstallationType::SOLO->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 40],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 3],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 40],
                    ['type' => EquipmentType::STRING_BOX->value, 'quantity' => 3],
                    ['type' => EquipmentType::CABO_TRONCO->value, 'quantity' => 100],
                ],
            ],

            // Project 7 - Empresa 1
            [
                'client' => $clients[3],
                'uf' => 'SC',
                'installation_type' => InstallationType::LAJE->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 50],
                    ['type' => EquipmentType::MICROINVERSOR->value, 'quantity' => 50],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 50],
                    ['type' => EquipmentType::ENDCAP->value, 'quantity' => 4],
                ],
            ],

            // Project 8 - Empresa 1
            [
                'client' => $clients[3],
                'uf' => 'GO',
                'installation_type' => InstallationType::METALICO->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 60],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 4],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 60],
                    ['type' => EquipmentType::CABO_VERMELHO->value, 'quantity' => 120],
                    ['type' => EquipmentType::CABO_PRETO->value, 'quantity' => 120],
                    ['type' => EquipmentType::STRING_BOX->value, 'quantity' => 4],
                ],
            ],

            // Project 9 - Empresa 2
            [
                'client' => $clients[4],
                'uf' => 'PE',
                'installation_type' => InstallationType::SOLO->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 100],
                    ['type' => EquipmentType::INVERSOR->value, 'quantity' => 5],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 100],
                    ['type' => EquipmentType::CABO_TRONCO->value, 'quantity' => 200],
                    ['type' => EquipmentType::STRING_BOX->value, 'quantity' => 5],
                ],
            ],

            // Project 10 - Empresa 2
            [
                'client' => $clients[4],
                'uf' => 'CE',
                'installation_type' => InstallationType::FIBROCIMENTO_MADEIRA->value,
                'equipment' => [
                    ['type' => EquipmentType::MODULO->value, 'quantity' => 80],
                    ['type' => EquipmentType::MICROINVERSOR->value, 'quantity' => 80],
                    ['type' => EquipmentType::ESTRUTURA->value, 'quantity' => 80],
                    ['type' => EquipmentType::CABO_VERMELHO->value, 'quantity' => 150],
                    ['type' => EquipmentType::CABO_PRETO->value, 'quantity' => 150],
                    ['type' => EquipmentType::ENDCAP->value, 'quantity' => 6],
                ],
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::updateOrCreate(
                [
                    'client_id' => $projectData['client']->id,
                    'uf' => $projectData['uf'],
                    'installation_type' => $projectData['installation_type'],
                ],
                [
                    'client_id' => $projectData['client']->id,
                    'uf' => $projectData['uf'],
                    'installation_type' => $projectData['installation_type'],
                ]
            );

            // Delete existing equipment and recreate
            $project->equipment()->delete();

            foreach ($projectData['equipment'] as $equipment) {
                ProjectEquipment::create([
                    'project_id' => $project->id,
                    'equipment_type' => $equipment['type'],
                    'quantity' => $equipment['quantity'],
                ]);
            }
        }

        $this->command->info('✅ 10 projects created successfully with equipment!');
        $this->command->info('📊 Coverage:');
        $this->command->info('   - All 6 installation types used');
        $this->command->info('   - All 9 equipment types used');
        $this->command->info('   - 10 different UFs (states)');
    }
}
