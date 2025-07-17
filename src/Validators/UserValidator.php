<?php

namespace App\Validators;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;

class UserValidator extends AbstractValidator {
  public function validateData(UserData $data): void {
    $this->resetErrors();

    $this->ensureNotEmpty($data->name, 'Nome');
    $this->validateEmail($data->email, 'Email');

    // Password validation
    if (strlen($data->password ?? '') < 8) {
      $this->addError('password', 'A senha deve ter pelo menos 8 caracteres.');
    }

    // Role validation
    $allowedRoles = ['client', 'seller'];
    $this->validateStatus($data->role, 'Cargo', $allowedRoles);

    $this->throwIfErrors();
  }

  public function validateDataEdit(UserDataEdit $data): void {
    $this->resetErrors();

    $this->ensureNotEmpty($data->name, 'Nome');
    $this->validateEmail($data->email, 'Email');

    $this->throwIfErrors();
  }
}
