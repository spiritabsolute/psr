<?php
namespace Tests\Framework\Container;

use PHPUnit\Framework\TestCase;
use Framework\Container\Container;
use Framework\Container\ServiceNotFoundException;

class ContainerTest extends TestCase
{
	public function testPrimitives()
	{
		$container = new Container();

		$container->set($id = "name", $value = 5);
		self::assertEquals($value, $container->get($id));

		$container->set($id = "name", $value = "string");
		self::assertEquals($value, $container->get($id));

		$container->set($id = "name", $value = ["array"]);
		self::assertEquals($value, $container->get($id));

		$container->set($id = "name", $value = new \stdClass());
		self::assertEquals($value, $container->get($id));
	}

	public function testNotFound()
	{
		$container = new Container();

		$this->expectException(ServiceNotFoundException::class);

		$container->get("email");
	}
}