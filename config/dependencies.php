<?php
use App\Http\Middleware;
use Framework\Container\Container;
use Framework\Http\Application;
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
$container->set(Router::class, function () {
	return new AuraRouterAdapter(new Aura\Router\RouterContainer());
});
$container->set(Resolver::class, function (Container $container) {
	return new Resolver($container);
});
$container->set(Middleware\BasicAuth::class, function (Container $container) {
	return new Middleware\BasicAuth($container->get("config")["users"]);
});
$container->set(Middleware\ErrorHandler::class, function (Container $container) {
	return new Middleware\ErrorHandler($container->get("config")["debug"]);
});