<?php

namespace App\DTOs;

enum TicketStatus: string {
  case RESERVED = 'reserved';
  case PURCHASED = 'purchased';
  case CANCELLED = 'cancelled';
  case EXPIRED = 'expired';
}

class TicketData {
  public function __construct(
    public readonly int $clientId,
    public readonly int $eventId,
    public readonly TicketStatus $status = TicketStatus::RESERVED,
  ) {}
}

