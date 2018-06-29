<?php
namespace App\Http\Action;

use App\Http\Middleware\BasicAuth;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Cabinet
{
	public function __invoke(ServerRequestInterface $request)
	{
		$username = $request->getAttribute(BasicAuth::ATTRIBUTE);
		return new HtmlResponse("I am logged in as " . $username);
	}
}