<?php
namespace App\Http\Middleware;

use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandler implements MiddlewareInterface
{
	private $debug;
	/**
	 * @var TemplateRenderer
	 */
	private $templateRenderer;

	public function __construct(bool $debug, TemplateRenderer $templateRenderer)
	{
		$this->debug = $debug;
		$this->templateRenderer = $templateRenderer;
	}

	/**
	 * Process an incoming server request.
	 *
	 * Processes an incoming server request in order to produce a response.
	 * If unable to produce the response itself, it may delegate to the provided
	 * request handler to do so.
	 *
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try
		{
			return $handler->handle($request);
		}
		catch (\Throwable $exception)
		{
			$view = ($this->debug ? "error/error-debug": "error/error");
			return new HtmlResponse($this->templateRenderer->render($view, [
				"request" => $request,
				"exception" => $exception
			]), $exception->getCode() ?: 500);
		}
	}
}