<?php
namespace App\Http\Middleware;

use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class PageNotFound
{
	/**
	 * @var TemplateRenderer
	 */
	private $templateRenderer;

	public function __construct(TemplateRenderer $templateRenderer)
	{
		$this->templateRenderer = $templateRenderer;
	}

	public function __invoke(ServerRequestInterface $request)
	{
		return new HtmlResponse($this->templateRenderer->render("error/404", [
			"request" => $request
		]), 404);
	}
}
