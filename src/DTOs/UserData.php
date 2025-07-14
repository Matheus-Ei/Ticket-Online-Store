<?php

namespace App\DTOs;

class UserData {
  public function __construct(
    public readonly string $name,
    public readonly string $email,
    public readonly string $passwordHash,
    public readonly string $role = 'client',
  ) {}
}

