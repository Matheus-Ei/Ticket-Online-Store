<?php

namespace App\Utils;

class MessageUtils {
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
