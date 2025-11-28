<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'installation_type' => $this->faker->randomElement(['Fibrocimento (Madeira)', 'Fibrocimento (Metálico)', 'Cerâmico', 'Metálico', 'Solo', 'Laje']),
            'uf' => $this->faker->randomElement(['SP', 'RJ', 'MG', 'ES']), // Valid UFs from Enum
        ];
    }
}
