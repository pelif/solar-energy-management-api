<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Project;

use App\Core\Domain\Project\Entities\Project as ProjectEntity;
use App\Core\Domain\Project\Enums\EquipmentType;
use App\Core\Domain\Project\Enums\InstallationType;
use App\Core\Domain\Project\Enums\UF;
use App\Core\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Core\Domain\Project\ValueObjects\Equipment;
use App\Models\Project as ProjectModel;
use App\Models\ProjectEquipment as ProjectEquipmentModel;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function create(ProjectEntity $project): ProjectEntity
    {
        $model = ProjectModel::create([
            'client_id' => $project->getClientId(),
            'uf' => $project->getUf()->value,
            'installation_type' => $project->getInstallationType()->value,
        ]);

        // Create equipment records
        foreach ($project->getEquipment() as $equipment) {
            ProjectEquipmentModel::create([
                'project_id' => $model->id,
                'equipment_type' => $equipment->getType()->value,
                'quantity' => $equipment->getQuantity(),
            ]);
        }

        // Reload with relationships
        $model->load('equipment');

        return $this->toEntity($model);
    }

    public function update(ProjectEntity $project): ProjectEntity
    {
        $model = ProjectModel::findOrFail($project->getId());
        $model->update([
            'uf' => $project->getUf()->value,
            'installation_type' => $project->getInstallationType()->value,
        ]);

        // Delete existing equipment and recreate
        $model->equipment()->delete();

        foreach ($project->getEquipment() as $equipment) {
            ProjectEquipmentModel::create([
                'project_id' => $model->id,
                'equipment_type' => $equipment->getType()->value,
                'quantity' => $equipment->getQuantity(),
            ]);
        }

        // Reload with relationships
        $model->load('equipment');

        return $this->toEntity($model);
    }

    public function delete(string $id): bool
    {
        return (bool) ProjectModel::destroy($id);
    }

    public function findById(string $id): ?ProjectEntity
    {
        $model = ProjectModel::with('equipment')->find($id);
        if (!$model) {
            return null;
        }
        return $this->toEntity($model);
    }

    public function findAll(array $filters = []): array
    {
        $query = ProjectModel::with('equipment');

        if (isset($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (isset($filters['uf'])) {
            $query->where('uf', $filters['uf']);
        }

        $models = $query->get();

        return $models->map(fn($model) => $this->toEntity($model))->toArray();
    }

    public function findByClientId(string $clientId): array
    {
        $models = ProjectModel::with('equipment')
            ->where('client_id', $clientId)
            ->get();

        return $models->map(fn($model) => $this->toEntity($model))->toArray();
    }

    private function toEntity(ProjectModel $model): ProjectEntity
    {
        $equipment = $model->equipment->map(function ($equipmentModel) {
            return new Equipment(
                type: EquipmentType::from($equipmentModel->equipment_type),
                quantity: $equipmentModel->quantity
            );
        })->toArray();

        return new ProjectEntity(
            id: $model->id,
            clientId: $model->client_id,
            uf: UF::from($model->uf),
            installationType: InstallationType::from($model->installation_type),
            equipment: $equipment
        );
    }
}
