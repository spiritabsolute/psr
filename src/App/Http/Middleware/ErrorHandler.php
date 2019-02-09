<?php
namespace App\Http\Middleware;

use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class ErrorHandler
{
	private $debug;
	/**
	 * @var TemplateRenderer
	 */
	private $templateRenderer;

	public function __construct($debug = false, TemplateRenderer $templateRenderer)
	{
		$this->debug = $debug;
		$this->templateRenderer = $templateRenderer;
	}

	public function __invoke(ServerRequestInterface $request, callable $next)
	{
		try
		{
			return $next($request);
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