<?php

namespace App\Validators;

use App\DTOs\UserData;

class UserValidator extends AbstractValidator {
  public function validateData(UserData $data): void {
    $this->resetErrors();

    $this->ensureNotEmpty($data->name, 'Name');
    $this->validateEmail($data->email, 'Email');

    // Password validation
    if (strlen($data->password ?? '') < 8) {
      $errors['password'] = 'The password must be at least 8 characters long.';
    }

    // Role validation
    $allowedRoles = ['client', 'seller'];
    $this->validateStatus($data->role, 'Role', $allowedRoles);

    $this->throwIfErrors();
  }
}
