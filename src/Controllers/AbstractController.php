<?php

namespace App\Controllers;

use App\Utils\GeralUtils;

abstract class AbstractController {
  protected function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
  }

  protected function getUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
  }

  protected function getUserRole(): ?string {
    return $_SESSION['user_role'] ?? null;
  }

  protected function setMessage(string $type, string $text): void {
    $_SESSION['message'] = [
      'type' => $type,
      'text' => $text
    ];
  }

  protected function getMessage(): array {
    // If a message is set in the session, retrieve it and clear it
    if (isset($_SESSION['message'])) {
      $message = $_SESSION['message'];
      unset($_SESSION['message']); 
      return $message;
    }

    return ['type' => '', 'text' => ''];
  }

  protected function renderView(string $view, array $data = [], string $layout = 'sidebar'): void {
    $data = array_merge($data, [
      'userRole' => $this->getUserRole(),
      'userId' => $this->getUserId(),
      'isLoggedIn' => $this->isLoggedIn(),
      'message' => $this->getMessage(),
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

  protected function navigate(string $url) {
    header("Location: $url");
    exit;
  }

  protected function checkLogin(?string $role = null) {
    // Check if the user is logged in
    if (!$this->isLoggedIn()) {
      $this->navigate('/users/login');
      return false;
    }

    // If a role is specified, check if the user has that role
    if ($role && $this->getUserRole() !== $role) {
      $this->navigate('/');
      return false;
    }

    return true;
  }
}
