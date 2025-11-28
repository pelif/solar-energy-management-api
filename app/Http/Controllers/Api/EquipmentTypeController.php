<?php

namespace App\Http\Controllers\Api;

use App\Core\Domain\Project\Enums\EquipmentType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class EquipmentTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/equipment-types",
     *     summary="List equipment types",
     *     description="Get all available equipment types for solar projects",
     *     tags={"Auxiliary"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="value", type="string", example="MODULO"),
     *                 @OA\Property(property="name", type="string", example="MODULO")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $types = array_map(
            fn($case) => ['value' => $case->value, 'name' => $case->name],
            EquipmentType::cases()
        );

        return response()->json($types);
    }
}
