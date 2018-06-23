<?php
namespace Tests\Framework\Http;

use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Aura\Router\RouterContainer;

class RouterTest extends TestCase
{
	public function testCorrectMethod()
	{
		$aura = new RouterContainer();
		$routes = $aura->getMap();

		$routes->get($nameGet = "blog", "/blog", $handlerGet = "handler_get");
		$routes->post($namePost = "blog_edit", "/blog", $handlerPost = "handler_post");

		$router = new AuraRouterAdapter($aura);

		$result = $router->match($this->buildRequest("GET", "/blog"));
		self::assertEquals($nameGet, $result->getName());
		self::assertEquals($handlerGet, $result->getHandler());
		
		$result = $router->match($this->buildRequest("POST", "/blog"));
		self::assertEquals($namePost, $result->getName());
		self::assertEquals($handlerPost, $result->getHandler());
	}

	public function testMissingMethod()
	{
		$aura = new RouterContainer();
		$routes = $aura->getMap();

		$routes->post("blog", "/blog", "handler_post");

		$router = new AuraRouterAdapter($aura);

		$this->expectException(RequestNotMatchedException::class);
		$router->match($this->buildRequest("DELETE", "/blog"));
	}

	public function testCorrectAttributes()
	{
		$aura = new RouterContainer();
		$routes = $aura->getMap();

		$routes->get($name = "blog_show", "/blog/{id}", "handler")->tokens(["id" => "\d+"]);

		$router = new AuraRouterAdapter($aura);

		$result = $router->match($this->buildRequest("GET", "/blog/5"));
		self::assertEquals($name, $result->getName());
		self::assertEquals(["id" => "5"], $result->getAttributes());
	}

	public function testIncorrectAttributes()
	{
		$aura = new RouterContainer();
		$routes = $aura->getMap();

		$routes->get($name = "blog_show", "/blog/{id}", "handler")->tokens(["id" => "\d+"]);

		$router = new AuraRouterAdapter($aura);

		$this->expectException(RequestNotMatchedException::class);
		$router->match($this->buildRequest("GET", "blog/slug"));
	}

	public function testGenerate()
	{
		$aura = new RouterContainer();
		$routes = $aura->getMap();

		$routes->get($name = "blog", "/blog", "handler");
		$routes->get($name = "blog_show", "/blog/{id}", "handler")->tokens(["id" => "\d+"]);

		$router = new AuraRouterAdapter($aura);

		self::assertEquals("/blog", $router->generate("blog"));
		self::assertEquals("/blog/5", $router->generate("blog_show", ["id" => 5]));
	}

	public function testGenerateMissingAttributes()
	{
		$aura = new RouterContainer();
		$routes = $aura->getMap();

		$routes->get($name = "blog_show", "/blog/{id}", "handler")->tokens(["id" => "\d+"]);

		$router = new AuraRouterAdapter($aura);

		$this->expectException(RouteNotFoundException::class);
		$router->generate("blog_show", ["slug" => "post"]);
	}

	private function buildRequest($method, $uri): ServerRequest
	{
		return (new ServerRequest())->withMethod($method)->withUri(new Uri($uri));
	}
}