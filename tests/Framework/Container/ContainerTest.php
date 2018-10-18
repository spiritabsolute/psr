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

	public function testCallback()
	{
		$container = new Container();

		$container->set($id = "name", function () {
			return new \stdClass();
		});

		self::assertNotNull($value = $container->get($id));
		self::assertInstanceOf(\stdClass::class, $value);
	}

	public function testSingleton()
	{
		$container = new Container();

		$container->set($id = "name", function () {
			return new \stdClass();
		});

		self::assertNotNull($value1 = $container->get($id));
		self::assertNotNull($value2 = $container->get($id));

		self::assertSame($value1, $value2);
	}

	public function testContainerPass()
	{
		$container = new Container();

		$container->set("param", $value = 15);
		$container->set($id = "name", function (Container $container) {
			$object = new \stdClass();
			$object->param = $container->get("param");
			return $object;
		});

		self::assertObjectHasAttribute("param", $object = $container->get($id));
		self::assertEquals($value, $object->param);
	}

	public function testNotFound()
	{
		$container = new Container();

		$this->expectException(ServiceNotFoundException::class);

		$container->get("email");
	}
}