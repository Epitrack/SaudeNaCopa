<?php
session_cache_limiter(false);
session_start();

require '../vendor/autoload.php';

require("../settings_routes.php");

$autoloadManager = new autoloadManager(null, autoloadManager::SCAN_ONCE);
$autoloadManager->addFolder('../app/controller');
$autoloadManager->addFolder('../app/model');
$autoloadManager->register();


if($debug){
    header('Access-Control-Allow-Origin: *');
}

$router = new Router();


$router->setBaseClass($baseClass);

$router->setBasePath($basePath);

$router->addRoutes($routes);

$router->set404Handler($erroHandler);

$router->run();

