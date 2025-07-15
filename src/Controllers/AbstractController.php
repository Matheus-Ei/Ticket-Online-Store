<?php

namespace App\Controllers;

use App\Utils\GeralUtils;
use App\Utils\MessageUtils;
use App\Utils\SessionUtils;

abstract class AbstractController {
  protected $model;
  protected $validator;

  protected function render(string $viewPath, array $data = [], string $layout = 'sidebar') {
    $userRole = SessionUtils::getUserRole();
    $userId = SessionUtils::getUserId();
    $isLoggedIn = SessionUtils::isLoggedIn();
    $message = MessageUtils::getMessage();

    $data = array_merge($data, [
      'userRole' => $userRole,
      'userId' => $userId,
      'isLoggedIn' => $isLoggedIn,
      'message' => $message,
    ]);

    extract($data);

    ob_start();

    include GeralUtils::basePath('resources/partials/message-toaster.php');
    include GeralUtils::basePath($viewPath);

    $content = ob_get_clean();

    require GeralUtils::basePath("resources/layouts/{$layout}.php");
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
