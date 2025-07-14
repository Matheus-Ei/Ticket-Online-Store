<?php

namespace App\Controllers;

use App\Utils\GeralUtils;
use App\Utils\SessionUtils;

abstract class AbstractController {
  protected $model;
  protected function renderView(string $viewPath, array $data = []) {
    extract($data); // Converts array keys to variables

    ob_start(); // Start output buffering

    include GeralUtils::basePath($viewPath); // Include the view file

    $content = ob_get_clean(); // Get the buffered content

    require GeralUtils::basePath('resources/layouts/main.php'); // Include the main layout file
  }

  protected function throwFormError(string $errorMessage, string $view, ?\Exception $errorObject = null) {
    // Set view with error message
    $data = [
      'title' => substr($errorMessage, 0, 22) . '...',
      'error' => $errorMessage,
    ];

    // Log the error message
    if ($errorObject) {
      $data['errorDetails'] = $errorObject->getMessage();
      error_log($errorObject->getMessage());
    } else {
      $data['errorDetails'] = 'No additional error details provided.';
    }

    // Render the view with the error
    $this->renderView($view, $data);

    return false;
  }

  protected function navigate(string $url) {
    header("Location: $url");
    exit;
  }

  protected function ensureLoggedIn(?string $role = null) {
    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
      return false;
    }

    if ($role && !SessionUtils::isRole($role)) {
      $this->navigate('/');
      return false;
    }

    return true;
  }
}
