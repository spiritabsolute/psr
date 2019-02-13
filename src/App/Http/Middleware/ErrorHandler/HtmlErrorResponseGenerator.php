<?php
namespace App\Http\Middleware\ErrorHandler;

use App\Http\Middleware\ErrorHandler;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class HtmlErrorResponseGenerator implements ErrorHandler\ErrorResponseGenerator
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

	public function generate(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
	{
		$view = ($this->debug ? "error/error-debug" : "error/error");

		return new HtmlResponse(
			$this->templateRenderer->render(
				$view,
				[
					"request" => $request,
					"exception" => $exception
				]
			), $exception->getCode() ? : Utils::getStatusCode($exception)
		);
	}
}