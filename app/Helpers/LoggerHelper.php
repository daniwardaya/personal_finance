<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Throwable;

class LoggerHelper
{
    /**
     * Log informasi.
     *
     * @param string $message
     * @param array $data
     */
    public static function logInfo(string $message, array $data = []): void
    {
        Log::info($message, $data);
    }

    /**
     * Log warning.
     *
     * @param string $message
     * @param array $data
     */
    public static function logWarning(string $message, array $data = []): void
    {
        Log::warning($message, $data);
    }

    /**
     * Log error dengan detail exception.
     *
     * @param string $message
     * @param Throwable $exception
     */
    public static function logError(string $message, Throwable $exception): void
    {
        Log::error($message, [
            'error_message' => $exception->getMessage(),
            'stack_trace'   => $exception->getTraceAsString(),
        ]);
    }
}