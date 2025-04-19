<?php

namespace ApiMultipurpose\Exceptions;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                "error" => true,
                "message" => "Route Not Found",
            ], 404);
        }


        return response()->json([
            "error" => true,
            "message" => $exception->getMessage()
        ], $this->getStatusCode($exception));

        parent::report($exception);
    }

    private function getStatusCode(Throwable $exception) {
        return method_field($exception, 'getStatusCode')
        ? $exception->getStatusCode() : 500;
    }
}
