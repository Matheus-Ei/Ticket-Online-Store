<?php

namespace App\Controllers;

use App\Utils\GeralUtils;
use App\Utils\MessageUtils;
use App\Utils\SessionUtils;

abstract class AbstractController {
  protected $service;
  protected $validator;

  protected function renderView(string $view, array $data = [], string $layout = 'sidebar'): void {
    $data = array_merge($data, [
      'userRole' => SessionUtils::getUserRole(),
      'userId' => SessionUtils::getUserId(),
      'isLoggedIn' => SessionUtils::isLoggedIn(),
      'message' => MessageUtils::getMessage(),
    ]);

    extract($data); // Create variables from the data array

    ob_start(); // Start output buffering

    // Includes the message toaster and the view
    include GeralUtils::basePath('resources/partials/message-toaster.php');
    include GeralUtils::basePath("resources/views/$view.php");

    $content = ob_get_clean(); // Get the buffered content

    // Include the content in the layout
    require GeralUtils::basePath("resources/layouts/{$layout}.php");
  }

  protected function renderError($error): void {
    $data = [
      'title' => 'Error',
      'errorMessage' => $error->getMessage(),
      'statusCode' => $error->getCode() ?: 500,
    ];

    $this->renderView('errors/error-card', $data);
  }

  protected function navigate(string $url) {
    header("Location: $url");
    exit;
  }

  protected function checkLogin(?string $role = null) {
    // Check if the user is logged in
    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
      return false;
    }

    // If a role is specified, check if the user has that role
    if ($role && SessionUtils::getUserRole() !== $role) {
      $this->navigate('/');
      return false;
    }

    return true;
  }
}
