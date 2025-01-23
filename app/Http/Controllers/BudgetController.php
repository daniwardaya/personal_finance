<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateBudgetRequest;
use App\Services\BudgetService;
use App\Http\Requests\StoreBudgetRequest;

class BudgetController extends Controller
{
    protected BudgetService $service;

    public function __construct(BudgetService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Put(
     *     path="/api/budgets/{userId}",
     *     summary="Update user's budget",
     *     operationId="updateUserBudget",
     *     tags={"Budget"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="User ID whose budget is to be updated",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Budget data to update",
     *         @OA\JsonContent(ref="#/components/schemas/Budget")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Budget successfully updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Anggaran berhasil diperbarui.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid input data",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         ),
     *     ),
     * )
     */
    public function updateBudget(UpdateBudgetRequest $request, int $userId): JsonResponse
    {
        $data = $request->validated();

        $this->service->updateUserBudget($userId, $data);

        return response()->json(['message' => 'Anggaran berhasil diperbarui.']);
    }

    /**
 * @OA\Post(
 *     path="/api/budgets/{userId}",
 *     summary="Create a new budget for a user",
 *     operationId="storeBudget",
 *     tags={"Budget"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="The ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data for creating a new budget",
 *         @OA\JsonContent(
 *             required={"category", "budget"},
 *             @OA\Property(property="category", type="string", enum={"Food", "Transportation", "Drink"}, example="Food"),
 *             @OA\Property(property="budget", type="number", format="float", example=1000000),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Budget successfully created",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Anggaran berhasil ditambahkan.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request data",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation errors")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 * )
 */

    public function store(StoreBudgetRequest $request, int $userId): JsonResponse
    {
        $data = $request->validated();

        $this->service->createUserBudget($userId, $data);

        return response()->json(['message' => 'Anggaran berhasil ditambahkan.']);
    }
}