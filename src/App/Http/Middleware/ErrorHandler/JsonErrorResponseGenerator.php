<?php
namespace App\Http\Middleware\ErrorHandler;

use App\Http\Middleware\ErrorHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class JsonErrorResponseGenerator implements ErrorHandler\ErrorResponseGenerator
{
	public function generate(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
	{
		return new JsonResponse(
			[
				"request" => $request,
				"exception" => $exception->getMessage()
			], Utils::getStatusCode($exception)
		);
	}
}