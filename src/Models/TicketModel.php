<?php

namespace App\Models;

use App\DTOs\TicketData;
use Core\Database;

class TicketModel extends AbstractModel {
  public function __construct(protected Database $database) {
    parent::__construct($database);
  }

  public function getById(int $id): ?array {
    $query = "SELECT 
                 t.id, 
                 t.status, 
                 e.name, 
                 e.start_time, 
                 e.end_time, 
                 e.location, 
                 e.description, 
                 e.image_url, 
                 t.created_at, 
                 t.client_id, 
                 c.name AS client_name, 
                 c.email AS client_email
              FROM tickets t 
                JOIN events e ON t.event_id = e.id 
                JOIN users c ON t.client_id = c.id
              WHERE t.id = :id";

    return $this->database->selectOne($query, ['id' => $id]);
  }

  public function getAll(): array {
    return $this->database->selectAll("SELECT * FROM tickets");
  }

  public function getPurchasedByClient(int $clientId): array {
    $query = "SELECT t.id, t.status, e.name, e.start_time, e.end_time, e.location
              FROM tickets t 
                JOIN events e ON t.event_id = e.id 
              WHERE t.client_id = :client_id AND t.status = 'purchased'";
    return $this->database->selectAll($query, ['client_id' => $clientId]);
  }

  public function create(TicketData $data): int {
    $query = "INSERT INTO tickets (status, client_id, event_id) 
              VALUES (:status, :client_id, :event_id)";

    $params = [
      'status' => $data->status,
      'client_id' => $data->clientId,
      'event_id' => $data->eventId
    ];

    return $this->database->insert($query, $params);
  }

  public function updateStatus(int $id, string $status): int {
    $query = "UPDATE tickets SET status = :status WHERE id = :id";
    return $this->database->execute($query, ['status' => $status, 'id' => $id]);
  }

  public function getReservedByClient(int $clientId, int $eventId): ?array {
    $query = "SELECT * FROM tickets WHERE event_id = :event_id AND client_id = :client_id AND status = 'reserved'";
    return $this->database->selectOne($query, ['event_id' => $eventId, 'client_id' => $clientId]);
  }

  public function delete(int $id): int {
    $query = "DELETE FROM tickets WHERE id = :id";
    return $this->database->execute($query, ['id' => $id]);
  }
}
