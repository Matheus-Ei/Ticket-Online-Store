<?php

namespace Config;

class Router {
  public $router = null;

  public $routes = [
    [
      "method" => "get",
      "endpoint" => "/users/register",
      "controller" => "App\Controllers\UserController",
      "action" => "registerForm"
    ],

    [
      "method" => "get",
      "endpoint" => "/users/login",
      "controller" => "App\Controllers\UserController",
      "action" => "loginForm"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/register",
      "controller" => "App\Controllers\UserController",
      "action" => "register"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/login",
      "controller" => "App\Controllers\UserController",
      "action" => "login"
    ],

    [
      "method" => "get",
      "endpoint" => "/",
      "controller" => "App\Controllers\EventController",
      "action" => "index"
    ],
  ];

  public function __construct() {
    $this->router = new \Bramus\Router\Router();
    $this->setup();
  }

  public function register($method, $endpoint, $controller, $action) {
    $this->router->$method($endpoint, "$controller@$action");
  }

  public function setup() {
    foreach($this->routes as $route) {
      $this->register(
        $route['method'],
        $route['endpoint'],
        $route['controller'],
        $route['action']
      );
    }

    // Setup a 404 handler
    $this->router->set404(function() {
      header("HTTP/1.0 404 Not Found");
      echo "404 Not Found";
    });
  }

  public function run() {
    $this->router->run();
  }
}
