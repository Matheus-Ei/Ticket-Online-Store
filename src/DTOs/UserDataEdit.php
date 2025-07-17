<?php

namespace App\DTOs;

class UserDataEdit {
  public function __construct(
    public readonly string $name,
    public readonly string $email
  ) {}
}

