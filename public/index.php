<?php

use Framework\Http\ActionResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Action;

require __DIR__."/../vendor/autoload.php";

### Initialization
$params = [
	"users" => [
		"admin" => "password"
	]
];

$auth = new BasicAuthMiddleware($params["users"]);

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get("home", "/", Action\Hello::class);
$routes->get("about", "/about", Action\About::class);

$routes->get("cabinet", "/cabinet", function (ServerRequestInterface $request) use ($auth) {

	$cabinet = new Action\Cabinet();
	return $auth($request, function (ServerRequestInterface $request) use ($cabinet) {
		return $cabinet($request);
	});
});

$routes->get("blog", "/blog", Action\Blog\Index::class);
$routes->get("blog_show", "/blog/{id}", Action\Blog\Show::class)->tokens(["id" => "\d+"]);

$router = new AuraRouterAdapter($aura);
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