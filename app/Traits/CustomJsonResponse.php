<?php

namespace ApiMultipurpose\Traits;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

trait CustomJsonResponse
{
    private function getContextPluralName(): string
    {
        return $this->contextPluralName ?? 'Items';
    }

    private function getMethodContext(): string
    {
        return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
    }

    private function getEntityName(): string
    {
        return str_replace('Service', '', class_basename(static::class));
    }

    private function execute(Closure $action, string $operation): JsonResponse
    {
        try {
            $result = $action();
            return $this->handleSuccess($result, $operation);
        } catch (\Throwable $e) {
            return $this->handleError($operation, $e);
        }
    }

    private function handleSuccess(mixed $result, string $operation): JsonResponse
    {
        if ($this->isEmptyResult($result)) {
            return $this->getNotFoundResponse($operation);
        }

        return response()->json([
            'message' => $this->getSuccessMessage($operation),
            'data'    => $result,
        ], $this->getSuccessStatus($operation));
    }

    private function getNotFoundResponse(string $operation): JsonResponse
    {
        $message = match ($operation) {
            'all' => "{$this->getContextPluralName()} not found",
            'getByUser', 'find', 'show' => "{$this->getEntityName()} not found",
            'store' => "{$this->getEntityName()} not created",
            'update' => "{$this->getEntityName()} not updated",
            'destroy' => "{$this->getEntityName()} not deleted",
            default => "Operation '{$operation}' failed"
        };

        $status = match ($operation) {
            'all', 'getByUser', 'find', 'show' => 404,
            default => 400,
        };

        return response()->json(['message' => $message], $status);
    }

    private function getSuccessMessage(string $operation): string
    {
        return match ($operation) {
            'all' => "{$this->getContextPluralName()} retrieved successfully",
            'getByUser' => "{$this->getContextPluralName()} retrieved successfully",
            'find' => "{$this->getEntityName()} found successfully",
            'show' => "{$this->getEntityName()} details retrieved successfully",
            'store' => "{$this->getEntityName()} created successfully",
            'update' => "{$this->getEntityName()} updated successfully",
            'destroy' => "{$this->getEntityName()} deleted successfully",
            default => "Operation '{$operation}' completed successfully"
        };
    }

    private function getSuccessStatus(string $operation): int
    {
        return $operation === 'store' ? 201 : 200;
    }

    private function handleError(string $operation, \Throwable $e): JsonResponse
    {
        return response()->json([
            'message' => "Error during {$operation} on {$this->getEntityName()}",
            'error'   => $e->getMessage(),
        ], 500);
    }

    private function isEmptyResult(mixed $result): bool
    {
        return match (true) {
            $result instanceof Collection => $result->isEmpty(),
            is_array($result)             => empty($result),
            is_null($result)              => true,
            is_bool($result)              => $result === false,
            default                       => false
        };
    }
}
