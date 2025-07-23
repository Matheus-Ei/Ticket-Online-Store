<?php

namespace App\Services;

use App\DTOs\EventData;
use App\Exceptions\NotFoundException;
use App\Models\EventModel;

class EventService {
  public function __construct(
    private EventModel $model
  ) {}

  public function get(int $id): array {
    $event = $this->model->getById($id);

    if (!$event) {throw new NotFoundException('Evento não encontrado.');}

    return $event;
  }

  public function getAll(?int $sellerId = null): array {
    if ($sellerId) {
      return $this->model->getAllBySeller($sellerId);
    }

    return $this->model->getAll();
  }

  public function getTicketsSold(int $eventId, ?int $userId, ?string $userRole): array {
    if ($userRole === 'seller') {
      return $this->model->getTicketsSold($eventId, $userId);
    }

    return [];
  }

  public function getWithOwner(int $id, int $sellerId): array {
    $event = $this->model->getById($id);

    if (!$event || $event['created_by'] !== $sellerId) {
      throw new NotFoundException('Evento não encontrado ou não foi criado por você.');
    }

    return $event;
  }

  public function getPurchased(int $clientId): array {
    return $this->model->getPurchasedByClient($clientId);
  }

  public function save(EventData $data, ?int $eventId): void {
    if ($eventId) {
      $this->model->update($eventId, $data);
    } else {
      $this->model->create($data);
    }
  }

  public function delete(int $id, int $sellerId): int {
    $event = $this->getWithOwner($id, $sellerId);

    if (!$event) {
      throw new NotFoundException('Evento não encontrado ou você não tem permissão para excluí-lo.');
    }

    return $this->model->delete($id);
  }
}
