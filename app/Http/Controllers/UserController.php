<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
* @OA\Info(
*     title="APIs For Personal Finance",
*     version="1.0.0",
* ),
* @OA\SecurityScheme(
*     securityScheme="bearerAuth",
*     in="header",
*     name="bearerAuth",
*     type="http",
*     scheme="bearer",
*     bearerFormat="JWT",
* ),
* @OA\Components(
*     @OA\Schema(
*         schema="UserResource",
*         type="object",
*         @OA\Property(property="id", type="integer", example=1),
*         @OA\Property(property="name", type="string", example="John Doe"),
*         @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
*         @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-23T12:00:00Z"),
*         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-23T12:00:00Z"),
*     ),
*     @OA\Schema(
 *         schema="Transaction",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="amount", type="number", example=1500),
 *         @OA\Property(property="category", type="string", example="Food"),
 *         @OA\Property(property="type", type="string", example="expense"),
 *         @OA\Property(property="date", type="string", format="date", example="2025-01-23"),
 *         @OA\Property(property="description", type="string", example="Lunch"),
 *         @OA\Property(property="user_id", type="integer", example=1),
 *     ),
 *     @OA\Schema(
 *         schema="TransactionRequest",
 *         type="object",
 *         required={"amount", "category", "type", "date", "user_id"},
 *         @OA\Property(property="amount", type="number", example=1500),
 *         @OA\Property(property="category", type="string", example="Food"),
 *         @OA\Property(property="type", type="string", example="expense"),
 *         @OA\Property(property="date", type="string", format="date", example="2025-01-23"),
 *         @OA\Property(property="description", type="string", example="Lunch", nullable=true),
 *         @OA\Property(property="user_id", type="integer", example=1),
 *     ),
 *     @OA\Schema(
 *         schema="Budget",
 *         type="object",
 *         required={"amount", "category"},
 *         @OA\Property(property="budget", type="number", example=5000),
 *         @OA\Property(property="category", type="string", example="Marketing"),
 *     ),
 *     @OA\Schema(
 *         schema="Reminder",
 *         type="object",
 *         required={"user_id", "title", "amount", "due_date"},
 *         @OA\Property(property="user_id", type="integer", example=1),
 *         @OA\Property(property="title", type="string", example="Pay bills"),
 *         @OA\Property(property="amount", type="number", example=500, format="float"),
 *         @OA\Property(property="due_date", type="string", format="date", example="2025-01-25"),
 *     ),
 *     @OA\Schema(
 *         schema="MonthlyReportResponse",
 *         type="object",
 *         required={"month", "total_income", "total_expense", "balance", "categories"},
 *         @OA\Property(property="month", type="string", example="January 2025"),
 *         @OA\Property(property="total_income", type="number", example=1000, format="float"),
 *         @OA\Property(property="total_expense", type="number", example=500, format="float"),
 *         @OA\Property(property="balance", type="number", example=500, format="float"),
 *         @OA\Property(
 *             property="categories",
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="category", type="string", example="Food"),
 *                 @OA\Property(property="amount", type="number", example=300, format="float"),
 *                 @OA\Property(property="percentage", type="number", example=30.0, format="float")
 *             )
 *         )
 *     ),
* )
*/
class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/api/users/register",
     *     summary="Register a new user",
     *     operationId="registerUser",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sukses mendaftarkan pengguna."),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid input data",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *         ),
     *     ),
     * )
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->userService->register($request->validated());

        return response()->json([
            'message' => 'Sukses mendaftarkan pengguna.',
            'data' => $user,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get a list of all users",
     *     operationId="getAllUsers",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of users with pagination",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             ),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10)
     *             )
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
    public function getAllUsers(): JsonResponse
    {
        $users = User::paginate(10);

        return response()->json([
            'data' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ], 200);
    }
}