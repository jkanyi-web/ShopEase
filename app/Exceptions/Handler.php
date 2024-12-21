<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // ...existing code...

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getStatusCode() ?: 400);
        }

        return parent::render($request, $exception);
    }
}
