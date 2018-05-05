<?php

$container['RulesAdminController'] = function ($container) {
    return new \App\Controllers\RulesAdminController($container);
};
$container['UserController'] = function ($container) {
    return new \App\Controllers\UserController($container);
};
$container['UserNetworkController'] = function ($container) {
    return new \App\Controllers\UserNetworkController($container);
};
$container['EventController'] = function ($container) {
    return new \App\Controllers\EventController($container);
};

