<?php
namespace App\Http\Action;

use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Hello
{
	private $templateRenderer;

	public function __construct(TemplateRenderer $templateRenderer)
	{
		$this->templateRenderer = $templateRenderer;
	}

	public function __invoke(ServerRequestInterface $request)
	{
		$name = $request->getQueryParams()["name"] ?? "Guest";

		return new HtmlResponse($this->templateRenderer->render("hello", ["name" => $name]));
	}
}