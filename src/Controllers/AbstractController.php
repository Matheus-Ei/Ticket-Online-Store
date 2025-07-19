<?php

namespace App\Controllers;

use App\Utils\GeralUtils;
use Core\Request;
use Core\Session;

abstract class AbstractController {
  public function __construct(
    private Session $session,
    private Request $request,
  ) {}

  protected function isLoggedIn(): bool {
    $userId = $this->session->get('user_id');
    return $userId && !empty($userId);
  }

  protected function getUserId(): ?int {
    return $this->session->get('user_id');
  }

  protected function getUserRole(): ?string {
    return $this->session->get('user_role');
  }

  protected function setMessage(string $type, string $text): void {
    $this->session->set('message', [
      'type' => $type,
      'text' => $text
    ]);
  }

  protected function getMessage(): array {
    if ($this->session->has('message')) {
      $message = $this->session->get('message');
      $this->session->remove('message');
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

  protected function navigate(string $url): void {
    header("Location: $url");
    exit;
  }

  protected function checkLogin(?string $role = null): bool {
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
