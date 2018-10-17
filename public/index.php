<?php

use App\Http\Action;
use App\Http\Middleware\Credentials;
use App\Http\Middleware\ErrorHandler;
use App\Http\Middleware\PageNotFound;
use App\Http\Middleware\Profiler;
use App\Http\Middleware\BasicAuth;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\Dispatch;
use Framework\Http\Middleware\Route;
use Framework\Http\Pipeline\Resolver;
use Framework\Http\Router\AuraRouterAdapter;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;

require __DIR__."/../vendor/autoload.php";

### Configuration
$container = new Container();
$container->set("debug", true);
$container->set("users", ["admin" => "password"]);

### Initialization
$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get("home", "/", Action\Hello::class);
$routes->get("about", "/about", Action\About::class);

$routes->get("cabinet", "/cabinet", [
	new BasicAuth($container->get("users")),
	Action\Cabinet::class
]);

$routes->get("blog", "/blog", Action\Blog\Index::class);
$routes->get("blog_show", "/blog/{id}", Action\Blog\Show::class)->tokens(["id" => "\d+"]);

$router = new AuraRouterAdapter($aura);

$resolver = new Resolver();
$app = new Application($resolver, new PageNotFound());

$app->pipe(new ErrorHandler($container->get("debug")));
$app->pipe(Credentials::class);
$app->pipe(Profiler::class);
$app->pipe(new Route($router));
$app->pipe(new Dispatch($resolver));

### Runnig
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

### Postprocessing

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);