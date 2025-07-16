<?php

namespace App\Services;

use App\DTOs\TicketData;
use App\Models\TicketModel;
use App\Models\EventModel;

class TicketService extends AbstractService {
  private $eventModel;

  public function __construct() {
    $this->model = new TicketModel();
    $this->eventModel = new EventModel();
  }

  public function get($id, $client_id) {
    $ticket = $this->model->getById((int) $id);

    if (!$ticket || $ticket['client_id'] !== $client_id) {
      throw new \Exception('Ticket not found or does not belong to the user.');
    }

    return $ticket;
  }

  public function getAll() {
    return $this->model->getAll();
  }

  public function getPurchased(int $clientId) {
    return $this->model->getPurchasedByClient($clientId);
  }

  public function purchase(int $clientId, int $eventId, ?int $ticketId) {
    if ($ticketId) {
      $ticket = $this->model->getById($ticketId);

      if (!$ticket || $ticket['client_id'] !== $clientId) {
        throw new \Exception('Ticket not found or does not belong to the user.');
      }

      switch ($ticket['status']) {
        case 'purchased': throw new \Exception('Ticket has already been purchased.');
        case 'expired':   throw new \Exception('Ticket has expired.');
        case 'canceled':  throw new \Exception('Ticket has been canceled.');
      }

      $this->model->updateStatus($ticketId, 'purchased');
      return $ticketId;
    }

    if (!$clientId || !$eventId) {
      throw new \Exception('Invalid client or event ID.');
    }

    $hasTickets = $this->eventModel->getNumberTickets($eventId) > 0;
    if (!$hasTickets) {
      throw new \Exception('No tickets available for this event.');
    }

    $ticketData = new TicketData(
      status: 'purchased',
      clientId: $clientId,
      eventId: $eventId
    );

    $newTicketId = $this->model->create($ticketData);
    return $newTicketId;
  }

  public function reserve(int $clientId, int $eventId) {
    $reservedTicket = $this->model->getReservedByClient($clientId, $eventId);
    if($reservedTicket) {
      return $reservedTicket['id'];
    }

    $ticketData = new TicketData(
      status: 'reserved',
      clientId: $clientId,
      eventId: $eventId
    );

    return $this->model->create($ticketData);
  }

  public function expireReservation(int $ticketId) {
    $ticket = $this->model->getById($ticketId);

    if (!$ticket || $ticket['status'] !== 'reserved') {
      throw new \Exception('Ticket not found or not reserved.');
    }

    $this->model->updateStatus($ticketId, 'expired');
  }
}
