<?php

use App\Http\Middleware\PageNotFound;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use App\Http\Middleware\ProfilerMiddleware;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Action;

require __DIR__."/../vendor/autoload.php";

### Initialization
$params = [
	"users" => [
		"admin" => "password"
	]
];

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get("home", "/", Action\Hello::class);
$routes->get("about", "/about", Action\About::class);

$routes->get("cabinet", "/cabinet", [
	new BasicAuthMiddleware($params["users"]),
	Action\Cabinet::class
]);

$routes->get("blog", "/blog", Action\Blog\Index::class);
$routes->get("blog_show", "/blog/{id}", Action\Blog\Show::class)->tokens(["id" => "\d+"]);

$router = new AuraRouterAdapter($aura);

$resolver = new MiddlewareResolver();
$app = new Application($resolver, new PageNotFound());
$app->pipe(ProfilerMiddleware::class);

### Runnig
$request = ServerRequestFactory::fromGlobals();

try
{
	$result = $router->match($request);
	foreach ($result->getAttributes() as $attribute => $value)
	{
		$request = $request->withAttribute($attribute, $value);
	}
	$app->pipe($result->getHandler());
}
catch (RequestNotMatchedException $exception) {}

$response = $app->run($request);

### Postprocessing
$response = $response->withHeader("X-Developer", ["SpiritAbsolute"]);

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);