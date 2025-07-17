<?php

namespace App\Validators;

use App\Exceptions\ValidationException;

abstract class AbstractValidator {
  private $errors = [];

  protected function resetErrors(): void {
    $this->errors = [];
  }

  protected function addError(string $fieldName, string $message): void {
    $this->errors[$fieldName] = $message;
  }

  protected function addValidationError(string $fieldName): void {
    $this->addError($fieldName, "O $fieldName é inválido.");
  }

  protected function validateInt($value, string $fieldName, int $min = 1): void {
    if ($value !== null && !filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min]])) {
      $this->addValidationError($fieldName);
    }
  }

  protected function validateFloat($value, string $fieldName, float $min = 0): void {
    if ($value !== null && !filter_var($value, FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => $min]])) {
      $this->addValidationError($fieldName);
    }
  }

  protected function ensureNotEmpty($value, string $fieldName): void {
    if (empty(trim($value))) {
      $this->addError($fieldName, "O $fieldName não pode estar vazio.");
    }
  }

  protected function validateStatus(string $status, string $fieldName, array $allowedStatuses): void {
    if ($status !== null && !in_array($status, $allowedStatuses)) {
      $error = "O $fieldName deve ser um destes: " . implode(', ', $allowedStatuses);
      $this->addError($fieldName, $error);
    }
  }

  protected function validateEmail($email, string $fieldName): void {
    if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->addValidationError($fieldName);
    }
  }

  protected function validateUrl($url, string $fieldName): void {
    if ($url !== null && !filter_var($url, FILTER_VALIDATE_URL)) {
      $this->addValidationError($fieldName);
    }
  }

  protected function throwIfErrors(): void {
    if (!empty($this->errors)) {
      throw new ValidationException($this->errors);
    }
  }

  public function validateId($id, string $fieldName = 'ID'): void {
    $this->resetErrors();
    $this->validateInt($id, $fieldName);
    $this->throwIfErrors();
  }

}
