<?php

require __DIR__.'/../vendor/autoload.php';

use App\Utils\GeralUtils;
use Core\Container;
use Core\Router;
use Core\Database;
use Core\DependencyProvider;

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

session_start(); 

// Setup the DI container
$container = new Container();
DependencyProvider::register($container);

$router = new Router($container);
$router->run();

