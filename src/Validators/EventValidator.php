<?php

namespace App\Validators;

use App\DTOs\EventData;
use DateTime;

class EventValidator extends AbstractValidator {
  public function validateSave(EventData $data): void {
    $this->resetErrors();

    // --- Required Fields ---
    $this->ensureNotEmpty($data->name, 'Name');
    $this->validateInt($data->createdBy, 'Created By');
    $this->validateInt($data->ticketQuantity, 'Ticket Quantity', 1);
    $this->validateFloat($data->ticketPrice, 'Ticket Price', 0);

    // Start Time validation
    $startTime = $data->startTime;
    if (!$startTime) {
      $errors['startTime'] = 'The start time must be a valid ISO 8601 date (e.g., 2025-12-31T20:00:00+00:00).';
    } elseif ($startTime < new DateTime()) {
      $errors['startTime'] = 'The start time must be in the future.';
    }

    // --- Optional Fields ---
    // End Time validation
    if (isset($data->endTime)) {
      $endTime = $data->endTime;
      if (!$endTime) {
        $errors['endTime'] = 'If provided, the end time must be a valid ISO 8601 date.';
      } elseif ($startTime && $endTime <= $startTime) {
        $errors['endTime'] = 'The end time must be after the start time.';
      }
    }
    if (isset($data->location) && !empty($data->location)) {
      $this->ensureNotEmpty($data->location, 'Location');
    }

    if (isset($data->description) && !empty($data->description)) {
      $this->ensureNotEmpty($data->description, 'Description');
    }

    if (isset($data->imageUrl) && !empty($data->imageUrl)) {
      $this->validateUrl($data->imageUrl, 'Image URL');
    }

    $this->throwIfErrors();
  }
}
