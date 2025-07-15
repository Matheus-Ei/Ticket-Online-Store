<?php

namespace App\Models;

use Config\Database;
use App\DTOs\EventData;

class EventModel {
  public function get(int $id) {
    $query = "SELECT 
                e.*, 
                e.ticket_quantity - COUNT(t.id) AS tickets_available 
              FROM events e 
                LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased' OR t.status = 'reserved')
              WHERE e.id = :id 
              GROUP BY e.id";

    return Database::selectOne($query, ['id' => $id]);
  }

  public function getAll() {
    $query = "SELECT 
                e.*, 
                e.ticket_quantity - COUNT(t.id) AS tickets_available 
              FROM events e 
                LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased' OR t.status = 'reserved')
              GROUP BY e.id";

    return Database::selectAll($query);
  }

  public function hasTickets(int $eventId) {
    $query = "SELECT COUNT(*) as count FROM tickets WHERE event_id = :event_id AND status IN ('purchased', 'reserved')";
    $result = Database::selectOne($query, ['event_id' => $eventId]);
    return $result['count'] > 0;
  }

  public function getPurchasedByClient(int $clientId) {
    $query = "SELECT e.* FROM events e 
              JOIN tickets t ON e.id = t.event_id 
              WHERE t.client_id = :client_id AND t.status = 'purchased'";

    return Database::selectAll($query, ['client_id' => $clientId]);
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

  public function existsAndIsOwner($eventId, $userId) {
    if (!$eventId || !$userId) {
        return false;
    }

    $query = "SELECT COUNT(*) as count FROM events WHERE id = :id AND created_by = :user_id";
    $event = Database::selectOne($query, ['id' => $eventId, 'user_id' => $userId]);

    return $event && $event['count'] > 0;
  }

  public function delete(int $id) {
    $query = "DELETE FROM events WHERE id = :id";
    return Database::execute($query, ["id" => $id]);
  }
}
