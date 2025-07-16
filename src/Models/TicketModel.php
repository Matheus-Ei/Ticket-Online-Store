<?php

namespace App\Models;

use App\DTOs\TicketData;
use Config\Database;

class TicketModel {
  private $eventModel;

  public function __construct() {
    $this->eventModel = new EventModel();
  }

  public function get($id) {
    // Get the ticket, without any id, substituing the client_id with user info, and the event_id with event info
    $query = "SELECT t.id, t.status, e.name, e.start_time, e.end_time, e.location, e.description, e.image_url, t.created_at
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

  private function create(TicketData $data) {
    $query = "INSERT INTO tickets (status, client_id, event_id) 
              VALUES (:status, :client_id, :event_id)";

    $params = [
      'status' => $data->status,
      'client_id' => $data->clientId,
      'event_id' => $data->eventId
    ];

    return Database::insert($query, $params);
  }

  public function existsAndIsOwner(int $id, int $clientId) {
    $query = "SELECT COUNT(*) FROM tickets WHERE id = :id AND client_id = :client_id";
    return Database::selectOne($query, ['id' => $id, 'client_id' => $clientId]) > 0;
  }

  private function updateStatus(int $id, string $status) {
    $query = "UPDATE tickets SET status = :status WHERE id = :id";
    return Database::execute($query, ['status' => $status, 'id' => $id]);
  }

  public function purchase(int $clientId, int $eventId, int $ticketId) {
    if ($ticketId) {
      $ticket = $this->get($ticketId);
      $isOwner = $this->existsAndIsOwner($ticketId, $clientId);

      if (!$isOwner) {
        throw new \Exception('Ticket not found or does not belong to the user.');
      }

      switch ($ticket['status']) {
        case 'purchased': throw new \Exception('Ticket has already been purchased.');
        case 'expired':   throw new \Exception('Ticket has expired.');
        case 'canceled':  throw new \Exception('Ticket has been canceled.');
      }

      $this->updateStatus($ticketId, 'purchased');
      return $ticketId;
    }

    // If no ticketId is provided, we assume the user is trying to purchase a new ticket for the event
    if ($eventId) {
      $hasTickets = $this->eventModel->hasTickets($eventId);

      if (!$hasTickets) {
          throw new \Exception('No tickets available for this event.');
      }

      $ticketData = new TicketData(
        status: 'purchased',
        clientId: $clientId,
        eventId: $eventId
      );

      $newTicketId = $this->create($ticketData);
      return $newTicketId;
    }

    throw new \Exception('Invalid purchase request.');
  }

  public function reserve(int $clientId, int $eventId) {
    $query = "SELECT * FROM tickets WHERE event_id = :event_id AND client_id = :client_id AND status = 'reserved'";
    $event = Database::selectOne($query, ['event_id' => $eventId, 'client_id' => $clientId]);

    // If a reserved ticket already exists for this event and client, return its ID
    if ($event) {
      return $event['id'];
    }

    $ticketData = new TicketData(
      status: 'reserved',
      clientId: $clientId,
      eventId: $eventId
    );

    return $this->create($ticketData);
  }

  public function expireReservation(int $ticketId) {
    if (!$ticketId || !is_numeric($ticketId)) {
      throw new \Exception('Invalid ticket ID.');
    }

    $ticket = $this->get($ticketId);
    if (!$ticket || $ticket['status'] !== 'reserved') {
      throw new \Exception('Ticket not found or not reserved.');
    }

    $this->updateStatus($ticketId, 'expired');
  }
}
