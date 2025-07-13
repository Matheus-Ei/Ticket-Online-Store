<?php

namespace App\Controllers;

use App\Utils\GeralUtils;

abstract class AbstractController {
  protected $model;

  protected function renderView(string $viewPath, array $data = []) {
    extract($data); // Converts array keys to variables

    ob_start(); // Start output buffering

    include GeralUtils::basePath($viewPath); // Include the view file

    $content = ob_get_clean(); // Get the buffered content

    require GeralUtils::basePath('resources/layouts/main.php'); // Include the main layout file
  }

  protected function navigate(string $url) {
    header("Location: $url");
    exit;
  }
}
