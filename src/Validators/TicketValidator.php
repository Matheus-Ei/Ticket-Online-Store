<?php

namespace App\Validators;

use App\DTOs\TicketData;

class TicketValidator extends AbstractValidator {
  public function validateData(TicketData $data): void {
    $this->resetErrors();

    $this->validateInt($data->clientId, 'ID do Cliente');
    $this->validateInt($data->eventId, 'ID do Evento');

    // Status validation
    if (isset($data->status) && !empty($data->status)) {
      $allowedStatus = ['reserved', 'purchased'];
      $this->validateStatus($data->status, 'status', $allowedStatus);
    }

    $this->throwIfErrors();
  }
}
