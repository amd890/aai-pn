<?php

namespace App\Support\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseService
{
    /**
     * Execute a callback inside a database transaction with structured error logging.
     *
     * @throws Throwable
     */
    protected function transactional(callable $callback, string $errorMessage = 'Transaction failed'): mixed
    {
        try {
            return DB::transaction($callback);
        } catch (Throwable $e) {
            Log::error("[$errorMessage] : " . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
