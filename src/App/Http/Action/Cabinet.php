<?php
namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Cabinet
{
	public function __invoke(ServerRequestInterface $request)
	{
		$username = $request->getAttribute("username");
		return new HtmlResponse("I am logged in as " . $username);
	}
}