<?php
namespace Tests\Framework\Http\Pipeline;

use Framework\Http\Pipeline\Resolver;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;

class ResolverTest extends TestCase
{
	/**
	 * @dataProvider getValidHandlers
	 * @param $handler
	 */
	public function testDirect($handler)
	{
		$resolver = new Resolver();
		$middleware = $resolver->resolve($handler);

		/**
		 * @var ResponseInterface $response
		 */
		$response = $middleware(
			(new ServerRequest())->withAttribute("attribute", $value = "value"),
			new Response(),
			new NotFoundMiddleware()
		);

		self::assertEquals([$value], $response->getHeader("X-Header"));
	}

	/**
	 * @dataProvider getValidHandlers
	 * @param $handler
	 */
	public function testNext($handler)
	{
		$resolver = new Resolver();
		$middleware = $resolver->resolve($handler);

		/**
		 * @var ResponseInterface $response
		 */
		$response = $middleware(
			(new ServerRequest())->withAttribute("next", true),
			new Response(),
			new NotFoundMiddleware()
		);

		self::assertEquals(404, $response->getStatusCode());
	}

	public function testArray()
	{
		$resolver = new Resolver();
		$middleware = $resolver->resolve([
			new DummyMiddleware(),
			new CallableMiddleware()
		]);

		/**
		 * @var ResponseInterface $response
		 */
		$response = $middleware(
			(new ServerRequest())->withAttribute("attribute", $value = "value"),
			new Response(),
			new NotFoundMiddleware()
		);

		self::assertEquals(["dummy"], $response->getHeader("X-Dummy"));
		self::assertEquals([$value], $response->getHeader("X-Header"));
	}

	public function getValidHandlers()
	{
		return [
			"CallableCallback" => [
				function (ServerRequestInterface $request, callable $next)
				{
					if ($request->getAttribute("next"))
					{
						return $next($request);
					}
					return (new HtmlResponse(""))->withHeader("X-Header", $request->getAttribute("attribute"));
				}
			],
			"CallableClass" => [CallableMiddleware::class],
			"CallableObject" => [new CallableMiddleware()],
			"DoublePassCallback" => [
				function (ServerRequestInterface $request, ResponseInterface $response, callable $next)
				{
					if ($request->getAttribute("next"))
					{
						return $next($request);
					}
					return $response->withHeader("X-Header", $request->getAttribute("attribute"));
				}
			],
			"DoublePassClass" => [DoublePassMiddleware::class],
			"DoublePassObject" => [new DoublePassMiddleware()],
			"InteropClass" => [InteropMiddleware::class],
			"InteropObject" => [new InteropMiddleware()]
		];
	}
}

class CallableMiddleware
{
	public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
	{
		if ($request->getAttribute("next"))
		{
			return $next($request);
		}

		return (new HtmlResponse(""))->withHeader("X-Header", $request->getAttribute("attribute"));
	}
}

class DoublePassMiddleware
{
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
	{
		if ($request->getAttribute("next"))
		{
			return $next($request);
		}

		return $response->withHeader("X-Header", $request->getAttribute("attribute"));
	}
}

class InteropMiddleware implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if ($request->getAttribute("next"))
		{
			return $handler->handle($request);
		}

		return (new HtmlResponse(""))->withHeader("X-Header", $request->getAttribute("attribute"));
	}
}

class NotFoundMiddleware
{
	public function __invoke(ServerRequestInterface $request)
	{
		return new EmptyResponse(404);
	}
}

class DummyMiddleware
{
	public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
	{
		return $next($request)->withHeader("X-Dummy", "dummy");
	}
}