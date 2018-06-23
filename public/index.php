<?php

use Framework\Http\Router\ActionResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use App\Http\Action;

require __DIR__."/../vendor/autoload.php";

### Initialization
$routes = new RouteCollection();

$routes->get("home", "/", Action\Hello::class);
$routes->get("about", "/about", Action\About::class);
$routes->get("blog", "/blog", Action\Blog\Index::class);
$routes->get("blog_show", "/blog/{id}", Action\Blog\Show::class, ["id" => "\d+"]);

$router = new Router($routes);
$resolver = new ActionResolver();

### Runnig
$request = ServerRequestFactory::fromGlobals();

try
{
	$result = $router->match($request);
	foreach ($result->getAttributes() as $attribute => $value)
	{
		$request = $request->withAttribute($attribute, $value);
	}
	$handler = $result->getHandler();
	/** @var callable $action */
	$action = $resolver->resolve($handler);
	$response = $action($request);
}
catch (RequestNotMatchedException $exception)
{
	$response = new JsonResponse(["error" => "Undefined page"], 404);
}

### Postprocessing
$response = $response->withHeader("X-Developer", ["Spirit"]);

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);