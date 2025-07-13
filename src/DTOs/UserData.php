<?php

namespace App\DTOs;

enum RoleType: string {
  case USER = 'user';
  case CLIENT = 'client';
}

class UserData {
  public function __construct(
    public readonly string $name,
    public readonly string $email,
    public readonly string $passwordHash,
    public readonly RoleType $role = RoleType::CLIENT,
  ) {}
}

