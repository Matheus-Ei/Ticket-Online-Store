<?php

namespace App\Utils;

class MessageUtils {
  public static function setMessage(string $type, string $text) {
    $_SESSION['message'] = [
      'type' => $type,
      'text' => $text
    ];
  }

  public static function getMessage() {
    if (isset($_SESSION['message'])) {
      $message = $_SESSION['message'];
      unset($_SESSION['message']); 
      return $message;
    }

    return ['type' => '', 'text' => ''];
  }
}
