<?php
namespace Tests\App\Http\Action;

use App\Http\Action\Cabinet;
use App\Http\Middleware\BasicAuth;
use Framework\Template\PhpRenderer;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class CabinetTest extends TestCase
{
	private $renderer;

	public function setUp(): void
	{
		parent::setUp();

		$this->renderer = new PhpRenderer("templates");
	}

	public function testContent()
	{
		$action = new Cabinet($this->renderer);

		$request = new ServerRequest();
		$response = $action($request);

		$username = $request->getAttribute(BasicAuth::ATTRIBUTE);

		self::assertEquals(200, $response->getStatusCode());
		self::assertContains("Cabinet of ".$username, $response->getBody()->getContents());
	}

}