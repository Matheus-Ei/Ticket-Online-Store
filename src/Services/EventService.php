<?php

namespace App\Services;

use App\DTOs\EventData;
use App\Models\EventModel;

class EventService extends AbstractService {
  public function __construct() {
    $this->model = new EventModel();
  }

  public function get($id) {
    return $this->model->getById($id);
  }

  public function getWithOwner(int $id, int $userId) {
    $event = $this->model->getById($id);

    if ($event && $event['created_by'] === $userId) {
      return $event;
    }

    return null;
  }

  public function getAll() {
    return $this->model->getAll();
  }

  public function getPurchased(int $clientId) {
    return $this->model->getPurchasedByClient($clientId);
  }

  public function create(EventData $data) {
    return $this->model->create($data);
  }

  public function update(int $id, EventData $data) {
    return $this->model->update($id, $data);
  }

  public function delete(int $id, int $userId) {
    $event = $this->getWithOwner($id, $userId);

    if (!$event) {
      throw new \Exception('Evento não encontrado ou você não tem permissão para excluí-lo.');
    }

    return $this->model->delete($id);
  }
}
