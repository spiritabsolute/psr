<?php
namespace Tests\App\Http\Action;

use App\Http\Action\About;
use Framework\Template\PhpRenderer;
use PHPUnit\Framework\TestCase;

class AboutTest extends TestCase
{
	private $renderer;

	public function setUp(): void
	{
		parent::setUp();

		$this->renderer = new PhpRenderer("templates");
	}

	public function testContent()
	{
		$action = new About($this->renderer);
		$response = $action();

		self::assertEquals(200, $response->getStatusCode());
		self::assertContains("I am a simple site", $response->getBody()->getContents());
	}
}