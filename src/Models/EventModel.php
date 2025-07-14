<?php

namespace App\Models;

use Config\Database;
use App\DTOs\EventData;

class EventModel {
  public function get(int $id) {
    return Database::selectOne("SELECT * FROM events WHERE id = :id", ['id' => $id]);
  }

  public function getAll() {
    return Database::selectAll("SELECT * FROM events");
  }

  public function getPurchasedByClient(int $clientId) {
    $query = "SELECT e.* FROM events e 
              JOIN tickets t ON e.id = t.event_id 
              WHERE t.client_id = :client_id AND t.status = 'purchased'";

    return Database::selectAll($query, ['client_id' => $clientId]);
  }

  public function getTicketsAvailable(int $eventId) {
    $query = "SELECT 
                e.id, 
                e.ticket_quantity - COUNT(t.id) AS tickets_available 
              FROM events e 
              LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased' OR t.status = 'reserved')
              WHERE e.id = :event_id 
              GROUP BY e.id";

    return Database::selectOne($query, ['event_id' => $eventId])['tickets_available'] ?? 0;
  }

  public function create(EventData $data) {
    $query = "INSERT INTO events (name, description, image_url, start_time, end_time, location, ticket_price, ticket_quantity, created_by) 
    VALUES (:name, :description, :image_url, :start_time, :end_time, :location, :ticket_price, :ticket_quantity, :created_by)";

    $params = [
      'name' => $data->name,
      'description' => $data->description,
      'image_url' => $data->imageUrl,
      'start_time' => $data->startTime->format('Y-m-d H:i:s'),
      'end_time' => $data->endTime?->format('Y-m-d H:i:s'),
      'location' => $data->location,
      'ticket_price' => $data->ticketPrice,
      'ticket_quantity' => $data->ticketQuantity,
      'created_by' => $data->createdBy
    ];

    return Database::insert($query, $params);
  }

  public function update(int $id, EventData $data) {
    $query = "UPDATE events SET 
    name = :name, 
    description = :description, 
    image_url = :image_url, 
    start_time = :start_time, 
    end_time = :end_time, 
    location = :location, 
    ticket_price = :ticket_price, 
    ticket_quantity = :ticket_quantity 
    WHERE id = :id";


    $params = [
      'id' => $id,
      'name' => $data->name,
      'description' => $data->description,
      'image_url' => $data->imageUrl,
      'start_time' => $data->startTime->format('Y-m-d H:i:s'),
      'end_time' => $data->endTime?->format('Y-m-d H:i:s'),
      'location' => $data->location,
      'ticket_price' => $data->ticketPrice,
      'ticket_quantity' => $data->ticketQuantity,
    ];

    return Database::execute($query, $params);
  }

  public function delete(int $id) {
    $query = "DELETE FROM events WHERE id = :id";
    return Database::execute($query, ["id" => $id]);
  }
}
