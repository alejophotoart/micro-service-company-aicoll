<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ServerErrorException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => 'Ha ocurrido un error inesperado... intentelo mas tarde'
        ], 500);
    } 
}
