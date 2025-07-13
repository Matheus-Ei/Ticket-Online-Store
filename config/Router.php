<?php

namespace Config;

class Router {
  public $router = null;

  public $routes = [
    [
      "method" => "get",
      "endpoint" => "/",
      "controller" => "App\Controllers\UserController",
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
  }

  public function run() {
    $this->router->run();
  }
}
