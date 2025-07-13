<?php

namespace App\DTOs;

class EventData {
  public function __construct(
    public readonly string $name,
    public readonly int $createdBy,
    public readonly int $ticketQuantity,
    public readonly float $ticketPrice,
    public readonly \DateTimeInterface $startTime,
    public readonly ?string $description = null,
    public readonly ?string $imageUrl = null,
    public readonly ?\DateTimeInterface $endTime = null,
    public readonly ?string $location = null,
  ) {}
}
