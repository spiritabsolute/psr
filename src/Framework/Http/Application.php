<?php
namespace Framework\Http;

use Framework\Http\Pipeline\Resolver;
use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
	private $resolver;
	private $default;

	public function __construct(Resolver $resolver, callable $default)
	{
		parent::__construct();
		$this->resolver = $resolver;
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
}