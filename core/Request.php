<?php

namespace Core;

class Request {
  private array $get;
  private array $post;

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
}

