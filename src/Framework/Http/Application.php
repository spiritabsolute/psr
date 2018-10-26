<?php
namespace Framework\Http;

use Framework\Http\Pipeline\Resolver;
use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
	private $resolver;
	private $router;
	private $default;

	public function __construct(Resolver $resolver, Router $router, callable $default)
	{
		parent::__construct();
		$this->resolver = $resolver;
		$this->router = $router;
		$this->default = $default;
	}

	public function pipe($middleware): void
	{
		parent::pipe($this->resolver->resolve($middleware));
	}

	public function run(ServerRequestInterface $request, ResponseInterface $response)
	{
		return $this($request, $response, $this->default);
	}

	public function addRoute($name, $path, $handler, array $methods, array $options= []): void
	{
		$this->router->addRoute($name, $path, $handler, $methods, $options);
	}

	public function addAnyRoute($name, $path, $handler, array $options= []): void
	{
		$this->addRoute($name, $path, $handler, [], $options);
	}

	public function addGetRoute($name, $path, $handler, array $options= []): void
	{
		$this->addRoute($name, $path, $handler, ["GET"], $options);
	}

	public function addPostRoute($name, $path, $handler, array $options= []): void
	{
		$this->addRoute($name, $path, $handler, ["POST"], $options);
	}

	public function addPutRoute($name, $path, $handler, array $options = []): void
	{
		$this->addRoute($name, $path, $handler, ["PUT"], $options);
	}

	public function addPatchRoute($name, $path, $handler, array $options = []): void
	{
		$this->addRoute($name, $path, $handler, ["PATCH"], $options);
	}

	public function addDeleteRoute($name, $path, $handler, array $options = []): void
	{
		$this->addRoute($name, $path, $handler, ["DELETE"], $options);
	}
}