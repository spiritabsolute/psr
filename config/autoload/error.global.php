<?php
use App\Http\Middleware\ErrorHandler\ErrorHandler;
use App\Http\Middleware\ErrorHandler\ErrorResponseGenerator;
use App\Http\Middleware\ErrorHandler\HtmlErrorResponseGenerator;
use Framework\Template\TemplateRenderer;
use Psr\Container\ContainerInterface;

return [
	"dependencies" => [
		"factories" => [
			ErrorHandler::class => function (ContainerInterface $container) {
				return new ErrorHandler($container->get(ErrorResponseGenerator::class));
			},
			ErrorResponseGenerator::class => function (ContainerInterface $container) {
				return new HtmlErrorResponseGenerator(
					$container->get("config")["debug"],
					$container->get(TemplateRenderer::class)
				);
			},
		]
	]
];