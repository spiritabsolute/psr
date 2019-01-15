<?php
use App\Http\Action;

/**
 * @var \Framework\Http\Application $app
 */

$app->addGetRoute("home", "/", Action\Home::class);
$app->addGetRoute("about", "/about", Action\About::class);
$app->addGetRoute("cabinet", "/cabinet", [
	$container->get(App\Http\Middleware\BasicAuth::class),
	Action\Cabinet::class
]);
$app->addGetRoute("blog", "/blog", Action\Blog\Index::class);
$app->addGetRoute("blog_show", "/blog/{id}", Action\Blog\Show::class, ["tokens" => ["id" => "\d+"]]);