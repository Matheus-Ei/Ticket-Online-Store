<?php

namespace App\Exceptions;

use InvalidArgumentException;

class ValidationException extends InvalidArgumentException {
  protected array $errors;

  public function __construct(array $errors, ?string $message = "The given data was invalid.") {
    // If the message is the default one and there are errors, use the first error message
    if ($message === "The given data was invalid." && !empty($errors)) {
      $firstError = reset($errors);

      $message = is_array($firstError) ? reset($firstError) : $firstError;
    }

    parent::__construct($message, 400);
    $this->errors = $errors;
  }

  public function getErrors(): array {
    return $this->errors;
  }
}
