<?php

namespace App\DTOs;

class UserData {
  public function __construct(
    public readonly string $name,
    public readonly string $email,
    public string $password,
    public string $role = 'client',
  ) {}
}

