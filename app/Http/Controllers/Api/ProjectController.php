<?php

namespace App\Http\Controllers\Api;

use App\Core\UseCase\Project\CreateProjectUseCase;
use App\Core\UseCase\Project\DeleteProjectUseCase;
use App\Core\UseCase\Project\DTO\EquipmentDto;
use App\Core\UseCase\Project\DTO\ProjectInputDto;
use App\Core\UseCase\Project\GetProjectUseCase;
use App\Core\UseCase\Project\ListProjectsUseCase;
use App\Core\UseCase\Project\UpdateProjectUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ProjectController extends Controller
{
    public function __construct(
        private CreateProjectUseCase $createProjectUseCase,
        private UpdateProjectUseCase $updateProjectUseCase,
        private DeleteProjectUseCase $deleteProjectUseCase,
        private ListProjectsUseCase $listProjectsUseCase,
        private GetProjectUseCase $getProjectUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="List all projects",
     *     description="Get a list of all projects with optional filters",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="Filter by client ID",
     *         required=false,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="uf",
     *         in="query",
     *         description="Filter by state (UF)",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="client_id", type="string", format="uuid"),
     *                 @OA\Property(property="uf", type="string", example="SP"),
     *                 @OA\Property(property="installation_type", type="string", example="FIBROCIMENTO"),
     *                 @OA\Property(
     *                     property="equipment",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="type", type="string", example="MODULO"),
     *                         @OA\Property(property="quantity", type="integer", example=10)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['client_id', 'uf']);
        $projects = $this->listProjectsUseCase->execute((object) $filters);
        return response()->json($projects);
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Create a new project",
     *     description="Create a new solar energy project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id","uf","installation_type","equipment"},
     *             @OA\Property(property="client_id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
     *             @OA\Property(property="uf", type="string", maxLength=2, example="SP"),
     *             @OA\Property(property="installation_type", type="string", example="FIBROCIMENTO", description="Valid values: FIBROCIMENTO, CERAMICO, METALICO, LAJE, SOLO"),
     *             @OA\Property(
     *                 property="equipment",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"type","quantity"},
     *                     @OA\Property(property="type", type="string", example="MODULO", description="Valid values: MODULO, INVERSOR, MICROINVERSOR, ESTRUTURA, STRINGBOX"),
     *                     @OA\Property(property="quantity", type="integer", minimum=1, example=10)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="client_id", type="string", format="uuid"),
     *             @OA\Property(property="uf", type="string"),
     *             @OA\Property(property="installation_type", type="string"),
     *             @OA\Property(property="equipment", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $equipmentDtos = array_map(
                fn($eq) => new EquipmentDto($eq['type'], $eq['quantity']),
                $data['equipment']
            );

            $input = new ProjectInputDto(
                clientId: $data['client_id'],
                uf: $data['uf'],
                installationType: $data['installation_type'],
                equipment: $equipmentDtos
            );

            $project = $this->createProjectUseCase->execute($input);
            return response()->json($project, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Get a specific project",
     *     description="Get detailed information about a specific project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="client_id", type="string", format="uuid"),
     *             @OA\Property(property="uf", type="string"),
     *             @OA\Property(property="installation_type", type="string"),
     *             @OA\Property(property="equipment", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $project = $this->getProjectUseCase->execute($id);
            return response()->json($project);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     summary="Update a project",
     *     description="Update an existing project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id","uf","installation_type","equipment"},
     *             @OA\Property(property="client_id", type="string", format="uuid"),
     *             @OA\Property(property="uf", type="string", maxLength=2, example="RJ"),
     *             @OA\Property(property="installation_type", type="string", example="CERAMICO"),
     *             @OA\Property(
     *                 property="equipment",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"type","quantity"},
     *                     @OA\Property(property="type", type="string", example="INVERSOR"),
     *                     @OA\Property(property="quantity", type="integer", minimum=1, example=5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="client_id", type="string", format="uuid"),
     *             @OA\Property(property="uf", type="string"),
     *             @OA\Property(property="installation_type", type="string"),
     *             @OA\Property(property="equipment", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();

        try {
            $equipmentDtos = array_map(
                fn($eq) => new EquipmentDto($eq['type'], $eq['quantity']),
                $data['equipment']
            );

            $input = new ProjectInputDto(
                clientId: $data['client_id'],
                uf: $data['uf'],
                installationType: $data['installation_type'],
                equipment: $equipmentDtos,
                id: $id
            );

            $project = $this->updateProjectUseCase->execute($input);
            return response()->json($project);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     summary="Delete a project",
     *     description="Delete an existing project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Project ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Project deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->deleteProjectUseCase->execute($id);
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
