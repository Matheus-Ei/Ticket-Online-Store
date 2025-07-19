<?php

namespace App\DTOs;

class UserDataEdit implements DataInterface {
  public function __construct(
    public readonly string $name,
    public readonly string $email
  ) {}
}

