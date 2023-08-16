<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorException extends Exception
{
	public function render($request): JsonResponse
	{
		return response()->json([
			'message' => $this->getMessage()
		], Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}