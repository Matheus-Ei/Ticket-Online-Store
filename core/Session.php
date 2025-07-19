<?php

namespace Core;

class Session {
  public function __construct() {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public function set(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
  }

  public function get(string $key, mixed $default = null): mixed {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
  }

  public function has(string $key): bool {
    return isset($_SESSION[$key]);
  }

  public function remove(string $key): void {
    unset($_SESSION[$key]);
  }

  public function regenerateId(bool $deleteOldSession = false): void {
    session_regenerate_id($deleteOldSession);
  }

  public function destroy(): void {
    session_destroy();
  }
}
