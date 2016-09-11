<?php
declare (strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();
$bootstrap = new \App\Bootstrap();

$bootstrap
    ->loadServices($app)
    ->loadControllers($app)
    ->errorHandler($app);

$app->run();
