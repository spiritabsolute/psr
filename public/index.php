<?php

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;

require __DIR__."/../vendor/autoload.php";

### Initialization
$request = ServerRequestFactory::fromGlobals();

### Action
$name = $request->getQueryParams()["name"] ?? "Guest";

$response = (new HtmlResponse("Hello, ".$name."!"))
	->withHeader("X-Developer", ["Spirit"]);

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);