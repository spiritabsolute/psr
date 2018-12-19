<?php
namespace Tests\App\Http\Action;

use App\Http\Action\Hello;
use Framework\Template\TemplateRenderer;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class HelloTest extends TestCase
{
	private $renderer;

	public function setUp(): void
	{
		parent::setUp();

		$this->renderer = new TemplateRenderer("templates");
	}

	public function testGuest()
	{
		$action = new Hello($this->renderer);

		$request = new ServerRequest();
		$response = $action($request);

		self::assertEquals(200, $response->getStatusCode());
		self::assertContains("Hello, Guest!", $response->getBody()->getContents());
	}

	public function testSpirit()
	{
		$action = new Hello($this->renderer);

		$request = (new ServerRequest())->withQueryParams(["name" => "Spirit"]);
		$response = $action($request);

		self::assertEquals(200, $response->getStatusCode());
		self::assertContains("Hello, Spirit!", $response->getBody()->getContents());
	}
}