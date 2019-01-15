<?php
namespace App\Http\Action;

use Framework\Template\TemplateRenderer;
use Zend\Diactoros\Response\HtmlResponse;

class About
{
	private $templateRenderer;

	public function __construct(TemplateRenderer $templateRenderer)
	{
		$this->templateRenderer = $templateRenderer;
	}

	public function __invoke()
	{
		return new HtmlResponse($this->templateRenderer->render("app/about"));
	}
}