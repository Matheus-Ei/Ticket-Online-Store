<?php

namespace App\Models;

use Core\Database;
use App\DTOs\EventData;

class EventModel extends AbstractModel {
  public function __construct(protected Database $database) {
    parent::__construct($database);
  }

  public function getById(int $id): ?array {
    $query = "SELECT 
                e.*, 
                e.ticket_quantity - COUNT(t.id) AS tickets_available 
              FROM events e 
                LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased' OR t.status = 'reserved')
              WHERE e.id = :id 
              GROUP BY e.id";

    return $this->database->selectOne($query, ['id' => $id]);
  }

  public function getAll(): array {
    $query = "SELECT 
                e.*, 
                e.ticket_quantity - COUNT(t.id) AS tickets_available 
              FROM events e 
                LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased' OR t.status = 'reserved')
              WHERE e.start_time > NOW()
              GROUP BY e.id
              HAVING (e.ticket_quantity - COUNT(t.id)) > 0;";

    return $this->database->selectAll($query);
  }

  public function getTicketsSold(int $eventId, int $sellerId): array {
    $query = "SELECT 
                t.status,
                t.created_at,
                u.name AS client_name,
                u.email AS client_email
            FROM tickets t 
              JOIN events e ON t.event_id = e.id 
              JOIN users u ON t.client_id = u.id
            WHERE t.event_id = :id AND e.created_by = :created_by
            ORDER BY t.created_at DESC";

    return $this->database->selectAll($query, ['id' => $eventId, 'created_by' => $sellerId]);
  }

  public function getAllBySeller(int $sellerId): array {
    $query = "SELECT 
                e.*, 
                e.ticket_quantity - COUNT(t.id) AS tickets_available 
            FROM events e 
              LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased' OR t.status = 'reserved')
            WHERE e.created_by = :created_by
            GROUP BY e.id";

    return $this->database->selectAll($query, ['created_by' => $sellerId]);
  }

  public function getNumberTickets(int $eventId): int {
    $query = "SELECT COUNT(*) as count FROM tickets WHERE event_id = :event_id AND status IN ('purchased', 'reserved')";
    $result = $this->database->selectOne($query, ['event_id' => $eventId]);
    return $result['count'];
  }

  public function getPurchasedByClient(int $clientId): array {
    $query = "SELECT e.*
              FROM events e 
                LEFT JOIN tickets t ON e.id = t.event_id AND (t.status = 'purchased')
              WHERE t.client_id = :client_id
              GROUP BY e.id";

    return $this->database->selectAll($query, ['client_id' => $clientId]);
  }

  public function create(EventData $data): int {
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

    return $this->database->insert($query, $params);
  }

  public function update(int $id, EventData $data): int {
    $query = "UPDATE events 
              SET 
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

    return $this->database->execute($query, $params);
  }

  public function delete(int $id): int {
    $query = "DELETE FROM events WHERE id = :id";
    return $this->database->execute($query, ["id" => $id]);
  }
}
