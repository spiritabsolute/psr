<?php
use App\Http\Middleware;
use Framework\Http\Application;
use Framework\Http\Pipeline\Resolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Framework\Template\Php\PhpRenderer;
use Framework\Template\Php\Extension\Route as PhpRendererRoute;
use Framework\Template\TemplateRenderer;
use Psr\Container\ContainerInterface;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
	"dependencies" => [
		"abstract_factories" => [
			ReflectionBasedAbstractFactory::class
		],
		"factories" => [
			Application::class => function (ContainerInterface $container) {
				return new Application(
					$container->get(Resolver::class),
					$container->get(Router::class),
					$container->get(Middleware\PageNotFound::class)
				);
			},
			Router::class => function () {
				return new AuraRouterAdapter(new Aura\Router\RouterContainer());
			},
			Resolver::class => function (ContainerInterface $container) {
				return new Resolver($container);
			},
			Middleware\ErrorHandler::class => function (ContainerInterface $container) {
				return new Middleware\ErrorHandler(
					$container->get("config")["debug"],
					$container->get(TemplateRenderer::class)
				);
			},
			TemplateRenderer::class => function (ContainerInterface $container) {
				$renderer = new PhpRenderer("../templates");
				$renderer->addExtension($container->get(PhpRendererRoute::class));
				return $renderer;
			},
		]
	],
	"debug" => false
];