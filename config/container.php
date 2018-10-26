<?php
use Framework\Container\Container;

$container = new Container();

$container->set("config", require "parameters.php");

require "dependencies.php";

return $container;