<?php

namespace Core;

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

class DependencyProvider {
  public static function register(Container $container): void {
    // Database connection as a singleton
    $container->bind(Database::class, function () {
      static $database = null;

      if ($database === null) {
        $database = new Database();
      }

      return new Database();
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
        $c->get(\App\Services\EventService::class),
        $c->get(EventValidator::class)
      );
    });
    $container->bind(UserController::class, function ($c) {
      return new UserController(
        $c->get(UserService::class),
        $c->get(UserValidator::class)
      );
    });
    $container->bind(TicketController::class, function ($c) {
      return new TicketController(
        $c->get(TicketService::class),
        $c->get(EventService::class),
        $c->get(EventValidator::class)
      );
    });

    // Validators bindings
    $container->bind(EventValidator::class, fn () => new EventValidator());
    $container->bind(UserValidator::class, fn () => new UserValidator());
    $container->bind(TicketValidator::class, fn () => new TicketValidator());
  }
}
