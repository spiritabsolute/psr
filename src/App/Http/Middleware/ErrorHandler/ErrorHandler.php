<?php
namespace App\Http\Middleware\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandler implements MiddlewareInterface
{
	private $generate;

	public function __construct(ErrorResponseGenerator $generate)
	{
		$this->generate = $generate;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try
		{
			return $handler->handle($request);
		}
		catch (\Throwable $exception)
		{
			return $this->generate->generate($request, $exception);
		}
	}
}