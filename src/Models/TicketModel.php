<?php

namespace App\Controllers;

use App\DTOs\TicketData;
use Config\Database;

class TicketModel {
  public function get(int $id) {
    return Database::selectOne("SELECT * FROM tickets WHERE id = :id", ['id' => $id]);
  }

  public function getAll() {
    return Database::selectAll("SELECT * FROM tickets");
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

  public function update(int $id, TicketData $data) {
    $query = "UPDATE tickets SET 
    status = :status, 
    client_id = :client_id, 
    event_id = :event_id 
    WHERE id = :id";

    $params = [
      'id' => $id,
      'status' => $data->status,
      'client_id' => $data->clientId,
      'event_id' => $data->eventId
    ];

    return Database::execute($query, $params);
  }

  public function delete(int $id) {
    $query = "DELETE FROM tickets WHERE id = :id";
    return Database::execute($query, ["id" => $id]);
  }
}
