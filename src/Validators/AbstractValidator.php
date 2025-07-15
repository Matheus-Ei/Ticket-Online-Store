<?php

namespace App\Validators;

use App\Exceptions\ValidationException;

abstract class AbstractValidator {
  protected $errors = [];

  protected function validateInt(int $value, string $fieldName, int $min = 1): void {
    if ($value !== null && !filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min]])) {
      $this->errors[$fieldName] = "A valid $fieldName is required.";
    }
  }

  protected function validateFloat(float $value, string $fieldName, float $min = 0): void {
    if ($value !== null && !filter_var($value, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => $min]])) {
      $this->errors[$fieldName] = "A valid $fieldName is required.";
    }
  }

  protected function ensureNotEmpty(string $value, string $fieldName): void {
    if (empty(trim($value))) {
      $this->errors[$fieldName] = "The $fieldName cannot be empty.";
    }
  }

  protected function validateStatus(string $status, string $fieldName, array $allowedStatuses): void {
    if ($status !== null && !in_array($status, $allowedStatuses)) {
      $this->errors[$fieldName] = "The $fieldName must be one of: " . implode(', ', $allowedStatuses);
    }
  }

  protected function validateEmail(string $email, string $fieldName): void {
    if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->errors[$fieldName] = "A valid $fieldName is required.";
    }
  }

  protected function validateUrl(string $url, string $fieldName): void {
    if ($url !== null && !filter_var($url, FILTER_VALIDATE_URL)) {
      $this->errors[$fieldName] = "A valid $fieldName is required.";
    }
  }

  protected function resetErrors(): void {
    $this->errors = [];
  }

  protected function throwIfErrors(): void {
    if (!empty($this->errors)) {
      throw new ValidationException($this->errors);
    }
  }
}
