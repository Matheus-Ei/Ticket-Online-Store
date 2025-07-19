<?php

require __DIR__.'/../vendor/autoload.php';

use App\Utils\ErrorUtils;
use App\Utils\GeralUtils;
use Core\DI\Container;
use Core\DI\Provider;
use Core\Router;

// Loads the dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Error handling for production environment
if(GeralUtils::getEnv("ENVIROMENT") === "production") {
  ini_set('display_errors', '0');
  ini_set('display_startup_errors', '0');
  ini_set('log_errors', '1');
  error_reporting(E_ALL & ~E_DEPRECATED);
  ini_set('error_log', '../errors.log');
}

// Setup the DI container
$container = new Container();
Provider::register($container);

try {
  // Setup router and run the application
  $router = new Router($container);
  $router->run();
} catch (Throwable $e) {
  ErrorUtils::handleException($e);
}

