<?php
namespace App\Http\Action;

use App\Http\Middleware\BasicAuth;
use Framework\Template\TemplateRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Cabinet
{
	private $templateRenderer;

	public function __construct(TemplateRenderer $templateRenderer)
	{
		$this->templateRenderer = $templateRenderer;
	}

	public function __invoke(ServerRequestInterface $request)
	{
		$username = $request->getAttribute(BasicAuth::ATTRIBUTE);
		return new HtmlResponse($this->templateRenderer->render("app/cabinet", [
			"username" => $username
		]));
	}
}