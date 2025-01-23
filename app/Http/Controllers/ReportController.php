<?php
namespace App\Http\Controllers;

use App\Http\Requests\MonthlyReportRequest;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

 /**
     * @OA\Post(
     *     path="/api/reports/monthly",
     *     summary="Generate Monthly Report",
     *     operationId="generateMonthlyReport",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Monthly report generation data",
     *         @OA\JsonContent(
     *             required={"user_id", "month", "year"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="month", type="integer", example=1),
     *             @OA\Property(property="year", type="integer", example=2025),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Monthly report successfully generated",
     *         @OA\JsonContent(ref="#/components/schemas/MonthlyReportResponse"),
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
    public function monthlyReport(MonthlyReportRequest $request)
    {
        $userId = $request->input('user_id');
        $month  = $request->input('month');
        $year   = $request->input('year');

        $report = $this->service->generateMonthlyReport($userId, $month, $year);

        return response()->json($report);
    }
}