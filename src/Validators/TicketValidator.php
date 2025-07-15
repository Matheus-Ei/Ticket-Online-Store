<?php

namespace App\Validators;

use App\DTOs\TicketData;

class TicketValidator extends AbstractValidator {
  public function validateData(TicketData $data): void {
    $this->resetErrors();

    $this->validateInt($data->clientId, 'Client ID');
    $this->validateInt($data->eventId, 'Event ID');

    // Status validation
    if (isset($data->status)) {
      $allowedStatus = ['reserved', 'purchased'];
      $this->validateStatus($data->status, 'status', $allowedStatus);
    }

    $this->throwIfErrors();
  }
}
