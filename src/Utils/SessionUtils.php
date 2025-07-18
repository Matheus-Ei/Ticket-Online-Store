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

  public static function setMessage(string $type, string $text): void {
    $_SESSION['message'] = [
      'type' => $type,
      'text' => $text
    ];
  }

  public static function getMessage(): array {
    // If a message is set in the session, retrieve it and clear it
    if (isset($_SESSION['message'])) {
      $message = $_SESSION['message'];
      unset($_SESSION['message']); 
      return $message;
    }

    return ['type' => '', 'text' => ''];
  }
}
