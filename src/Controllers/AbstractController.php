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

  protected function renderWithSidebar(string $viewPath, array $data = []) {
    extract($data); // Converts array keys to variables

    ob_start(); // Start output buffering

    include GeralUtils::basePath($viewPath); // Include the view file

    $content = ob_get_clean(); // Get the buffered content

    $sidebar = [
      'title' => 'Menu',
      'links' => [],
    ];

    $userRole = SessionUtils::getUserRole();
    switch ($userRole) {
      case 'seller':
        $sidebar['links'] = [
          ['label' => 'Profile', 'url' => '/users/profile'],
          ['label' => 'Create Event', 'url' => '/events/save'],
          ['label' => 'Events', 'url' => '/events'],
        ];
        break;
      case 'client':
        $sidebar['links'] = [
          ['label' => 'Events', 'url' => '/events'],
          ['label' => 'Profile', 'url' => '/users/profile'],
          ['label' => 'Purchased Tickets', 'url' => '/tickets/purchased'],
          ['label' => 'Purchased Events', 'url' => '/events/purchased'],
        ];
        break;
      default:
        $sidebar = null;
    }

    if (!SessionUtils::isLoggedIn()) {
      $sidebar = null;
    }

    require GeralUtils::basePath('resources/layouts/sidebar-main.php'); // Include the main layout file
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
    $this->renderView($view, $data);

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
