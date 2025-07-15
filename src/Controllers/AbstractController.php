<?php

namespace App\Controllers;

use App\Utils\GeralUtils;
use App\Utils\SessionUtils;

abstract class AbstractController {
  protected $model;

  protected function render(string $viewPath, array $data = [], string $layout = 'sidebar') {
    $userRole = SessionUtils::getUserRole();
    $isLoggedIn = SessionUtils::isLoggedIn();

    extract($data);

    ob_start();

    include GeralUtils::basePath($viewPath);

    $content = ob_get_clean();

    require GeralUtils::basePath("resources/layouts/{$layout}.php");
  }

  protected function throwViewError(string $view, $error) {
    // If error is an Exception, get the message
    if ($error instanceof \Exception) {
      $error = $error->getMessage();
    } elseif (!is_string($error)) {
      $error = 'An unexpected error occurred.';
    }

    // Set view with error message
    $data = [
      'title' => substr($error, 0, 22) . '...',
      'error' => $error,
    ];

    // Log the error message
    error_log($error);

    // Render the view with the error
    $this->render($view, $data);

    return false;
  }

  protected function navigate(string $url) {
    header("Location: $url");
    exit;
  }

  protected function ensureLoggedIn(?string $role = null) {
    // Check if the user is logged in
    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
      return false;
    }

    // If a role is specified, check if the user has that role
    if ($role && !SessionUtils::isRole($role)) {
      $this->navigate('/');
      return false;
    }

    return true;
  }
}
