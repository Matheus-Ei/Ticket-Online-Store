<?php

namespace App\DTOs;

class TicketData implements DataInterface {
  public function __construct(
    public readonly int $clientId,
    public readonly int $eventId,
    public readonly string $status = 'reserved'
  ) {}
}

