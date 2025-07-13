<?php

require __DIR__.'/../vendor/autoload.php';

use App\Utils\GeralUtils;
use Config\Router;
use Config\Database;

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

Database::connect();

$router = new Router();
$router->run();

