<?php
namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;

class PageNotFound
{
	public function __invoke(ServerRequestInterface $request)
	{
		return new HtmlResponse("Undefined page", 404);
	}
}