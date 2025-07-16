<?php

namespace App\Models;

use App\DTOs\TicketData;
use Config\Database;

class TicketModel {
  public function getById(int $id) {
    $query = "SELECT t.id, t.status, e.name, e.start_time, e.end_time, e.location, e.description, e.image_url, t.created_at, t.client_id
              FROM tickets t 
                JOIN events e ON t.event_id = e.id 
              WHERE t.id = :id";

    return Database::selectOne($query, ['id' => $id]);
  }

  public function getAll() {
    return Database::selectAll("SELECT * FROM tickets");
  }

  public function getPurchasedByClient(int $clientId) {
    $query = "SELECT t.id, t.status, e.name, e.start_time, e.end_time, e.location
              FROM tickets t 
                JOIN events e ON t.event_id = e.id 
              WHERE t.client_id = :client_id AND t.status = 'purchased'";
    return Database::selectAll($query, ['client_id' => $clientId]);
  }

  public function create(TicketData $data) {
    $query = "INSERT INTO tickets (status, client_id, event_id) 
              VALUES (:status, :client_id, :event_id)";

    $params = [
      'status' => $data->status,
      'client_id' => $data->clientId,
      'event_id' => $data->eventId
    ];

    return Database::insert($query, $params);
  }

  public function updateStatus(int $id, string $status) {
    $query = "UPDATE tickets SET status = :status WHERE id = :id";
    return Database::execute($query, ['status' => $status, 'id' => $id]);
  }

  public function getReservedByClient(int $clientId, int $eventId) {
    $query = "SELECT * FROM tickets WHERE event_id = :event_id AND client_id = :client_id AND status = 'reserved'";
    return Database::selectOne($query, ['event_id' => $eventId, 'client_id' => $clientId]);
  }
}
