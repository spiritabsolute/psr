<?php
use Framework\Container\Container;

$container = new Container(require "dependencies.php");

$container->set("config", require "parameters.php");

return $container;