<?php

require_once __DIR__ .'/../vendor/autoload.php';

$app = new Sarracenia\App();
$app['debug'] = true;
$app->register(new Dionaea\Modules\ModulesExtension(__DIR__ .'/../src'));

$app->dispatch();
