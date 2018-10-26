<?php
namespace Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class AuraRouterAdapter implements Router
{
	private $aura;

	public function __construct(RouterContainer $aura)
	{
		$this->aura = $aura;
	}

	public function match(ServerRequestInterface $request): Result
	{
		$matcher = $this->aura->getMatcher();
		if ($route = $matcher->match($request))
		{
			return new Result($route->name, $route->handler, $route->attributes);
		}

		throw new RequestNotMatchedException($request);
	}

	public function generate($name, array $params = []): string
	{
		$generator = $this->aura->getGenerator();

		try
		{
			return $generator->generate($name, $params);
		}
		catch (RouteNotFound $exception)
		{
			throw new RouteNotFoundException($name, $params, $exception);
		}
	}

	public function addRoute($name, $path, $handler, array $methods, array $options): void
	{
		$map = $this->aura->getMap();

		$route = new Route();

		$route->name($name);
		$route->path($path);
		$route->handler($handler);

		if ($methods)
		{
			$route->allows($methods);
		}

		foreach ($options as $key => $value)
		{
			switch ($key)
			{
				case "tokens":
					$route->tokens($value);
					break;
				case "defaults":
					$route->defaults($value);
					break;
				case "wildcard":
					$route->wildcard($value);
					break;
				default:
					throw new \InvalidArgumentException("Undefined option ". $key);
			}
		}

		$map->addRoute($route);
	}
}