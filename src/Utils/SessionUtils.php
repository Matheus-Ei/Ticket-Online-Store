<?php

namespace App\Utils;

class SessionUtils {
  public static function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
  }

  public static function getUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
  }

  public static function getUserRole(): ?string {
    return $_SESSION['user_role'] ?? null;
  }
}
