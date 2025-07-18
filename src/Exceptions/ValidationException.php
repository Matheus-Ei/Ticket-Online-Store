<?php

namespace App\Exceptions;

use InvalidArgumentException;

class ValidationException extends InvalidArgumentException {
  protected ?array $errors;
  protected ?object $oldInput;

  public function __construct(?array $errors = null, ?object $input = null, ?string $message = "Os dados fornecidos são inválidos.") {
    // If the message is the default one and there are errors, use the first error message
    if ($message === "Os dados fornecidos são inválidos." && !empty($errors)) {
      $firstError = reset($errors);

      $message = is_array($firstError) ? reset($firstError) : $firstError;
    }

    parent::__construct($message, 400);

    $this->errors = $errors;
    $this->oldInput = $input;
  }

  public function getErrors(): array {
    return $this->errors;
  }

  public function getInputData(): ?object {
    return $this->oldInput;
  }
}
