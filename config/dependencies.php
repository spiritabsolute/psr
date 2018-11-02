<?php
use App\Http\Middleware;
use Framework\Container\Container;
use Framework\Http\Application;
use Framework\Http\Pipeline\Resolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;

return [
	Application::class => function (Container $container) {
		return new Application(
			$container->get(Resolver::class),
			$container->get(Router::class),
			new Middleware\PageNotFound()
		);
	},
	Router::class => function () {
		return new AuraRouterAdapter(new Aura\Router\RouterContainer());
	},
	Resolver::class => function (Container $container) {
		return new Resolver($container);
	},
	Middleware\BasicAuth::class => function (Container $container) {
		return new Middleware\BasicAuth($container->get("config")["users"]);
	},
	Middleware\ErrorHandler::class => function (Container $container) {
		return new Middleware\ErrorHandler($container->get("config")["debug"]);
	},
];