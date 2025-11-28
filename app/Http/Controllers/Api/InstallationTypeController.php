<?php

namespace App\Http\Controllers\Api;

use App\Core\Domain\Project\Enums\InstallationType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class InstallationTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/installation-types",
     *     summary="List installation types",
     *     description="Get all available installation types for solar projects",
     *     tags={"Auxiliary"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="value", type="string", example="FIBROCIMENTO"),
     *                 @OA\Property(property="name", type="string", example="FIBROCIMENTO")
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
            InstallationType::cases()
        );

        return response()->json($types);
    }
}
