<?php

namespace Core;

use Core\DI\Container;

class Router {
  public $router = null;
  protected $container = null;

  public $routes = [
    // Users routes
    [
      "method" => "get",
      "endpoint" => "/users/register",
      "controller" => "App\Controllers\UserController",
      "action" => "registerForm"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/register",
      "controller" => "App\Controllers\UserController",
      "action" => "register"
    ],

    [
      "method" => "get",
      "endpoint" => "/users/login",
      "controller" => "App\Controllers\UserController",
      "action" => "loginForm"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/login",
      "controller" => "App\Controllers\UserController",
      "action" => "login"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/logout",
      "controller" => "App\Controllers\UserController",
      "action" => "logout"
    ],

    [
      "method" => "get",
      "endpoint" => "/users/profile",
      "controller" => "App\Controllers\UserController",
      "action" => "viewProfile"
    ],

    [
      "method" => "get",
      "endpoint" => "/users/edit",
      "controller" => "App\Controllers\UserController",
      "action" => "editForm"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/edit",
      "controller" => "App\Controllers\UserController",
      "action" => "edit"
    ],

    [
      "method" => "post",
      "endpoint" => "/users/delete",
      "controller" => "App\Controllers\UserController",
      "action" => "delete"
    ],


    // Tickets routes
    [
      "method" => "get",
      "endpoint" => "/tickets/purchased",
      "controller" => "App\Controllers\TicketController",
      "action" => "viewPurchased"
    ],

    [
      "method" => "get",
      "endpoint" => "/tickets/generate-pdf/{ticketId}",
      "controller" => "App\Controllers\TicketController",
      "action" => "generatePdf"
    ],

    [
      "method" => "get",
      "endpoint" => "/tickets/buy",
      "controller" => "App\Controllers\TicketController",
      "action" => "buyForm"
    ],

    [
      "method" => "post",
      "endpoint" => "/tickets/buy",
      "controller" => "App\Controllers\TicketController",
      "action" => "buy"
    ],

    [
      "method" => "post",
      "endpoint" => "/tickets/expire",
      "controller" => "App\Controllers\TicketController",
      "action" => "expireReservation"
    ],

    [
      "method" => "post",
      "endpoint" => "/tickets/reserve",
      "controller" => "App\Controllers\TicketController",
      "action" => "reserve"
    ],

    [
      "method" => "get",
      "endpoint" => "/tickets/{id}",
      "controller" => "App\Controllers\TicketController",
      "action" => "viewSpecific"
    ],

    // Events routes
    [
      "method" => "get",
      "endpoint" => "/events/purchased",
      "controller" => "App\Controllers\EventController",
      "action" => "viewPurchased"
    ],

    [
      "method" => "get",
      "endpoint" => "/events/save",
      "controller" => "App\Controllers\EventController",
      "action" => "saveForm"
    ],

    [
      "method" => "post",
      "endpoint" => "/events/save",
      "controller" => "App\Controllers\EventController",
      "action" => "save"
    ],

    [
      "method" => "post",
      "endpoint" => "/events/delete/{id}",
      "controller" => "App\Controllers\EventController",
      "action" => "delete"
    ],

    [
      "method" => "get",
      "endpoint" => "/events/seller",
      "controller" => "App\Controllers\EventController",
      "action" => "viewSellerEvents"
    ],

    [
      "method" => "get",
      "endpoint" => "/events",
      "controller" => "App\Controllers\EventController",
      "action" => "index"
    ],

    [
      "method" => "get",
      "endpoint" => "/events/{id}",
      "controller" => "App\Controllers\EventController",
      "action" => "viewSpecific"
    ],

    [
      "method" => "get",
      "endpoint" => "/",
      "controller" => "App\Controllers\EventController",
      "action" => "index"
    ],
  ];

  public function __construct(Container $container) {
    $this->router = new \Bramus\Router\Router();
    $this->container = $container;
    $this->setup();
  }

  private function register($method, $endpoint, $controller, $action) {
    $this->router->$method($endpoint, function(...$params) use ($controller, $action) {
      $controllerInstance = $this->container->get($controller); // Get the controller instance from the container
      call_user_func_array([$controllerInstance, $action], $params); // Call the action method on the controller instance
    });  
  }

  private function setup() {
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
      require_once __DIR__ . '/../resources/views/errors/page-not-found.php';
    });
  }

  public function run() {
    $this->router->run();
  }
}
