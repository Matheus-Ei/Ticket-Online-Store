<?php

namespace App\Models;

use App\DTOs\TicketData;
use Config\Database;

class TicketModel {
  private $eventModel;

  public function __construct() {
    $this->eventModel = new EventModel();
  }

  public function get(int $id) {
    return Database::selectOne("SELECT * FROM tickets WHERE id = :id", ['id' => $id]);
  }

  public function getAll() {
    return Database::selectAll("SELECT * FROM tickets");
  }

  public function getPurchasedByClient(int $clientId) {
    $query = "SELECT * FROM tickets WHERE client_id = :client_id AND status = 'purchased'";
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

  public function purchase($clientId, $eventId, $ticketId) {
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
      return $this->get($ticketId);
    }

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
      return $this->get($newTicketId);
    }

    throw new \Exception('Invalid purchase request.');
  }

  public function reserve(int $clientId, int $eventId) {
    $ticketData = new TicketData(
      status: 'reserved',
      clientId: $clientId,
      eventId: $eventId
    );

    return $this->create($ticketData);
  }
}
