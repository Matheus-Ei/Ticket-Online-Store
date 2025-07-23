<?php

namespace App\DTOs;

class TicketData {
  public function __construct(
    public readonly int $clientId,
    public readonly int $eventId,
    public readonly string $status = 'reserved'
  ) {}
}

