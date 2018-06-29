<?php

use App\Http\Middleware\PageNotFound;
use Framework\Http\ActionResolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
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

$routes->get("cabinet", "/cabinet", function (ServerRequestInterface $request) use ($params) {
	$pipeline = new Pipeline();

	$pipeline->pipe(new ProfilerMiddleware());
	$pipeline->pipe(new BasicAuthMiddleware($params["users"]));
	$pipeline->pipe(new Action\Cabinet());
	
	return $pipeline($request, new PageNotFound());
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
	$pageNotFound = new PageNotFound();
	$response = $pageNotFound($request);
}

### Postprocessing
$response = $response->withHeader("X-Developer", ["Spirit"]);

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);