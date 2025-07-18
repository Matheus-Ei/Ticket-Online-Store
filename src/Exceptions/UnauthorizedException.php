<?php

namespace App\Exceptions;

use InvalidArgumentException;

class UnauthorizedException extends InvalidArgumentException {
  public function __construct($message = 'Acesso não autorizado a este recurso') {
    parent::__construct($message, 401);
  }
}
