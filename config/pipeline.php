<?php
use App\Http\Middleware;
use Framework\Http\Middleware\Dispatch;
use Framework\Http\Middleware\Route;

/**
 * @var \Framework\Container\Container $container
 * @var \Framework\Http\Application $app
 */

$app->pipe($container->get(Middleware\ErrorHandler::class));
$app->pipe(Middleware\Credentials::class);
$app->pipe(Middleware\Profiler::class);
$app->pipe($container->get(Route::class));
$app->pipe($container->get(Dispatch::class));