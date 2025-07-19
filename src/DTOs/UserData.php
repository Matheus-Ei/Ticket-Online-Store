<?php

namespace App\DTOs;

class UserData implements DataInterface {
  public function __construct(
    public readonly string $name,
    public readonly string $email,
    public readonly string $role = 'client',
    public string $password,
  ) {}
}

