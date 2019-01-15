<?php
namespace Tests\App\Http\Action;

use App\Http\Action\Home;
use Framework\Template\PhpRenderer;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
	private $renderer;

	public function setUp(): void
	{
		parent::setUp();

		$this->renderer = new PhpRenderer("templates");
	}

	public function testGuest()
	{
		$action = new Home($this->renderer);
		$response = $action();

		self::assertEquals(200, $response->getStatusCode());
		self::assertContains("Hello, Guest!", $response->getBody()->getContents());
	}
}