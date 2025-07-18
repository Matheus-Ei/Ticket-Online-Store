<?php

namespace Core;

class Container {
  protected $bindings = [];

  public function bind($key, $value) {
    $this->bindings[$key] = $value;
  }

  public function get($key) {
    if (!isset($this->bindings[$key])) {
      throw new \Exception("No binding found for {$key}");
    }

    $resolver = $this->bindings[$key];

    return $resolver($this);
  }
}
