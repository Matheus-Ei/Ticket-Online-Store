<?php

namespace App\Services;

use App\DTOs\EventData;
use App\Exceptions\NotFoundException;
use App\Models\EventModel;

class EventService extends AbstractService {
  public function __construct(
    private EventModel $model
  ) {}

  public function get($id) {
    $event = $this->model->getById($id);

    if (!$event) {throw new NotFoundException('Evento não encontrado.');}

    return $event;
  }

  public function getTicketsSold(int $eventId, int $sellerId) {
    return $this->model->getTicketsSold($eventId, $sellerId);
  }

  public function getWithOwner(int $id, int $sellerId) {
    $event = $this->model->getById($id);

    if (!$event || $event['created_by'] !== $sellerId) {
      throw new NotFoundException('Evento não encontrado ou não foi criado por você.');
    }

    return $event;
  }

  public function getAll(?int $sellerId = null) {
    if ($sellerId) {
      return $this->model->getAllBySeller($sellerId);
    }

    return $this->model->getAll();
  }

  public function getPurchased(int $clientId) {
    return $this->model->getPurchasedByClient($clientId);
  }

  public function save($eventId, EventData $data) {
    if ($eventId) {
      $this->model->update($eventId, $data);
    } else {
      $this->model->create($data);
    }
  }

  public function update(int $id, EventData $data) {
    $event = $this->model->getById($id);

    if (!$event) {
      throw new NotFoundException('Evento não encontrado ou você não tem permissão para atualizá-lo.');
    }

    return $this->model->update($id, $data);
  }

  public function delete(int $id, int $sellerId) {
    $event = $this->getWithOwner($id, $sellerId);

    if (!$event) {
      throw new NotFoundException('Evento não encontrado ou você não tem permissão para excluí-lo.');
    }

    return $this->model->delete($id);
  }
}
