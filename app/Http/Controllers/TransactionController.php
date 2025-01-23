<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;



class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="Get all transactions for the authenticated user",
     *     operationId="getAllTransactions",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="List of transactions",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Transaction")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    
        $transactions = $this->service->getAllTransactions($userId);
    
        return response()->json([
            'user_id' => $userId, // Debugging
            'data' => $transactions,
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/transactions",
     *     summary="Create a new transaction",
     *     operationId="storeTransaction",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionRequest")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Transaction"),
     *             @OA\Property(property="message", type="string", example="Transaction created successfully.")
     *         )
     *     )
     * )
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $transaction = Transaction::create($data);

        return response()->json([
            'data' => $transaction,
            'message' => 'Transaction created successfully.',
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     summary="Get a specific transaction",
     *     operationId="showTransaction",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Transaction details",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transaction not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $transaction = $this->service->getRepository()->findById($id, auth()->id());

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    /**
     * @OA\Put(
     *     path="/api/transactions/{id}",
     *     summary="Update a specific transaction",
     *     operationId="updateTransaction",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Transaction updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction updated successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transaction not found")
     *         )
     *     )
     * )
     */
    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = $this->service->getRepository()->findById($id, auth()->id());
        $data = $request->validated();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $this->service->updateTransaction($transaction, $data);
        return response()->json(['message' => 'Transaction updated successfully.']);
    }

    /**
     * @OA\Delete(
     *     path="/api/transactions/{id}",
     *     summary="Delete a specific transaction",
     *     operationId="deleteTransaction",
     *     tags={"Transactions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Transaction deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Transaction not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $transaction = $this->service->getRepository()->findById($id, auth()->id());
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $this->service->deleteTransaction($transaction);
    
        return response()->json(['message' => 'Transaction deleted successfully.']);
    }
}