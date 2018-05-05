<?php

/* Autoload vendor modules */
require __DIR__ . "/../vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\OauthServer\OauthServer;
use Chadicus\Slim\OAuth2\Middleware;
use App\Models\ApiTokens;

require __DIR__ . "/Settings.php";

$app = new \Slim\App($settings);

$container = $app->getcontainer();
$container['n'] = $container['settings']['n'];
/* Logger Intialisation */
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

/* Eloquent database library */
$container['db'] = function ($c) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c->get('settings')['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['UserController'] = function ($container) {
    return new \App\Controllers\UserController($container);
};


require __DIR__ . "/AddControllers.php";


$container['view'] = new \Slim\Views\PhpRenderer("../app/Views/templates/");

$app->get('/login', function ($request, $response, $args) {
    return $this->view->render($response, 'login.phtml');
});
$app->get('/logout', function ($request, $response, $args) {
    return $this->view->render($response, 'logout.phtml');
});
/* Middleware intialisation */
require __DIR__ . "/../app/routes.php";
