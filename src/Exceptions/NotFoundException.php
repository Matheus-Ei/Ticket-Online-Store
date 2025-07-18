<?php

namespace App\Exceptions;

use InvalidArgumentException;

class NotFoundException extends InvalidArgumentException {
    public function __construct($message = 'Recurso não encontrado ou inválido.') {
        parent::__construct($message, 404);
    }
}
