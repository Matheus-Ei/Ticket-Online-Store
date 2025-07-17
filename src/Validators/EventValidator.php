<?php

namespace App\Validators;

use App\DTOs\EventData;
use DateTime;

class EventValidator extends AbstractValidator {
  public function validateSave(EventData $data): void {
    $this->resetErrors();

    // --- Required Fields ---
    $this->ensureNotEmpty($data->name, 'Nome');
    $this->validateInt($data->createdBy, 'Criado por');
    $this->validateInt($data->ticketQuantity, 'Quantidade de ingressos', 1);
    $this->validateFloat($data->ticketPrice, 'Valor do Ingresso', 0);

    // Start Time validation
    $startTime = $data->startTime;
    if (!$startTime) {
      $this->addError('startTime', 'A data de início é obrigatória no formato ISO 8601.');
    } elseif ($startTime < new DateTime()) {
      $this->addError('startTime', 'A data de início não pode ser no passado.');
    }

    // --- Optional Fields ---
    // End Time validation
    if (isset($data->endTime) && !empty($data->endTime)) {
      if ($startTime && $data->endTime <= $startTime) {
        $this->addError('endTime', 'A data de término deve ser posterior à data de início.');
      }
    }

    if (isset($data->location) && !empty($data->location)) {
      $this->ensureNotEmpty($data->location, 'Localização');
    }

    if (isset($data->description) && !empty($data->description)) {
      $this->ensureNotEmpty($data->description, 'Descrição');
    }

    if (isset($data->imageUrl) && !empty($data->imageUrl)) {
      $this->validateUrl($data->imageUrl, 'URL da Imagem');
    }

    $this->throwIfErrors();
  }
}
