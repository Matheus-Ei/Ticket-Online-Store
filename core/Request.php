<?php

namespace Core;

class Request {
  private array $get;
  private array $post;
  private ?array $json = null;

  public function __construct() {
    $this->get = $_GET;
    $this->post = $_POST;
  }

  public function get(string $key, $default = null) {
    return $this->get[$key] ?? $default;
  }

  public function post(string $key, $default = null) {
    return $this->post[$key] ?? $default;
  }

  public function json(string $key, $default = null) {
    if ($this->json === null) {
      $input = file_get_contents('php://input');
      $this->json = json_decode($input, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        $this->json = [];
      }
    }

    if ($key === null) {
      return $this->json;
    }

    return $this->json[$key] ?? $default;
  }
}

