<?php
namespace App\Http\Action\Blog;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class Show
{
	public function __invoke(ServerRequestInterface $request)
	{
		$id = $request->getAttribute("id");

		if ($id > 2)
		{
			return new JsonResponse(["error" => "Undefined page"], 404);
		}

		return new JsonResponse(["id" => $id, "title" => "Post #".$id]);
	}
}