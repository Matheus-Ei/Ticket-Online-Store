<?php

namespace Core\DI;

use Core\Database;
use Core\Request;
use Core\Session;
use App\Controllers\EventController;
use App\Controllers\TicketController;
use App\Controllers\UserController;
use App\Models\EventModel;
use App\Models\TicketModel;
use App\Models\UserModel;
use App\Services\EventService;
use App\Services\TicketService;
use App\Services\UserService;
use App\Validators\EventValidator;
use App\Validators\TicketValidator;
use App\Validators\UserValidator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Provider {
  public static function register(Container $container): void {
    // Database connection as a singleton
    $container->bind(Database::class, function () {
      static $database = null;

      if ($database === null) {
        $database = new Database();
      }

      return new Database();
    });

    // Session manager as a singleton
    $container->bind(Session::class, function () {
      static $sessionManager = null;

      if ($sessionManager === null) {
        $sessionManager = new Session();
      }

      return $sessionManager;
    });

    $container->bind(Request::class, fn () => new Request());

    // Setup Monolog logger
    $container->bind(LoggerInterface::class, function () {
      $log = new Logger('app');
      $log->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::DEBUG));
      return $log;
    });

    // Models bindings
    $container->bind(EventModel::class, function ($c) {
      return new EventModel($c->get(Database::class));
    });
    $container->bind(UserModel::class, function ($c) {
      return new UserModel($c->get(Database::class));
    });
    $container->bind(TicketModel::class, function ($c) {
      return new TicketModel($c->get(Database::class));
    });

    // Services bindings
    $container->bind(EventService::class, function ($c) {
      return new EventService($c->get(EventModel::class));
    });
    $container->bind(UserService::class, function ($c) {
      return new UserService($c->get(UserModel::class));
    });
    $container->bind(TicketService::class, function ($c) {
      return new TicketService(
        $c->get(TicketModel::class),
        $c->get(EventModel::class)
      );
    });

    // Controllers bindings
    $container->bind(EventController::class, function ($c) {
      return new EventController(
        $c->get(Session::class),
        $c->get(Request::class),
        $c->get(\App\Services\EventService::class),
        $c->get(EventValidator::class)
      );
    });
    $container->bind(UserController::class, function ($c) {
      return new UserController(
        $c->get(Session::class),
        $c->get(Request::class),
        $c->get(UserService::class),
        $c->get(UserValidator::class)
      );
    });
    $container->bind(TicketController::class, function ($c) {
      return new TicketController(
        $c->get(Session::class),
        $c->get(Request::class),
        $c->get(TicketService::class),
        $c->get(TicketValidator::class),
        $c->get(EventService::class),
      );
    });

    // Validators bindings
    $container->bind(EventValidator::class, fn () => new EventValidator());
    $container->bind(UserValidator::class, fn () => new UserValidator());
    $container->bind(TicketValidator::class, fn () => new TicketValidator());
  }
}
