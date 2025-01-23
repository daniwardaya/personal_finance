<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MonitorResponseTime
{
    /**
     * Menangani permintaan masuk dan mengukur waktu respon.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Catat waktu mulai
        $startTime = Carbon::now();

        // Melanjutkan ke proses selanjutnya
        $response = $next($request);

        // Hitung waktu respons
        $endTime  = Carbon::now();
        $duration = $endTime->diffInMilliseconds($startTime);

        // Log waktu respon untuk endpoint tertentu
        Log::info('Response time for ' . $request->method() . ' ' . $request->path() . ': ' . $duration . ' ms');

        // Mengembalikan respons yang telah diproses
        return $response;
    }
}