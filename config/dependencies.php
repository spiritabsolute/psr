<?php
use App\Http\Middleware;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Middleware\Dispatch;
use Framework\Http\Middleware\Route;
use Framework\Http\Pipeline\Resolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;

$container->set(Application::class, function (Container $container) {
	return new Application(
		$container->get(Resolver::class),
		$container->get(Router::class),
		new Middleware\PageNotFound()
	);
});
$container->set(Resolver::class, function () {
	return new Resolver();
});
$container->set(Middleware\BasicAuth::class, function (Container $container) {
	return new Middleware\BasicAuth($container->get("config")["users"]);
});
$container->set(Middleware\ErrorHandler::class, function (Container $container) {
	return new Middleware\ErrorHandler($container->get("config")["debug"]);
});
$container->set(Route::class, function (Container $container) {
	return new Route($container->get(Router::class));
});
$container->set(Dispatch::class, function (Container $container) {
	return new Dispatch($container->get(Resolver::class));
});
$container->set(Router::class, function () {
	return new AuraRouterAdapter(new Aura\Router\RouterContainer());
});