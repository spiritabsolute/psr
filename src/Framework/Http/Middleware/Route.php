<?php
namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\Resolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class Route
{
	private $router;
	private $resolver;

	public function __construct(Router $router, Resolver $resolver)
	{
		$this->router = $router;
		$this->resolver = $resolver;
	}

	public function __invoke(ServerRequestInterface $request, callable $next)
	{
		try
		{
			$result = $this->router->match($request);
			foreach ($result->getAttributes() as $attribute => $value)
			{
				$request = $request->withAttribute($attribute, $value);
			}
			$middleware = $this->resolver->resolve($result->getHandler());
			return $middleware($request, $next);
		}
		catch (RequestNotMatchedException $exception)
		{
			return $next($request);
		}
	}
}