<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderRequest;
use App\Services\ReminderService;
use Illuminate\Http\JsonResponse;

class ReminderController extends Controller
{
    protected $service;

    public function __construct(ReminderService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/reminders",
     *     summary="Create a new reminder",
     *     operationId="createReminder",
     *     tags={"Reminders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reminder data to create",
     *         @OA\JsonContent(ref="#/components/schemas/Reminder")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Reminder successfully created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reminder added successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/Reminder"),
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
    public function store(StoreReminderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $reminder = $this->service->createReminder($data);

        return response()->json(['message' => 'Reminder added successfully.', 'data' => $reminder], 201);
    }
}