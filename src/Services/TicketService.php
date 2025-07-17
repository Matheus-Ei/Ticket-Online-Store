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
      throw new \Exception('Ingresso não encontrado ou não pertence ao usuário.', 404);
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
        throw new \Exception('Ingresso não encontrado ou não pertence ao usuário.', 404);
      }

      switch ($ticket['status']) {
        case 'purchased': throw new \Exception('O ingresso já foi comprado.', 400);
        case 'expired':   throw new \Exception('O ingresso expirou.', 400);
        case 'canceled':  throw new \Exception('O ingresso foi cancelado.', 400);
      }

      $this->model->updateStatus($ticketId, 'purchased');
      return $ticketId;
    }

    if (!$clientId || !$eventId) {
      throw new \Exception('ID do cliente e ID do evento são necessários.', 400);
    }

    $hasTickets = $this->eventModel->getNumberTickets($eventId) > 0;
    if (!$hasTickets) {
      throw new \Exception('Nenhum ingresso disponível para este evento.', 404);
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
    $hasTickets = $this->eventModel->getById($eventId)['tickets_available'] > 0;
    if (!$hasTickets) {
      throw new \Exception('Nenhum ingresso disponível para este evento.', 404);
    }

    // Verify if the client already has a reserved ticket for this event
    $reservedTicket = $this->model->getReservedByClient($clientId, $eventId);
    if($reservedTicket) {
      return $this->model->getById($reservedTicket['id']);
    }

    $ticketData = new TicketData(
      status: 'reserved',
      clientId: $clientId,
      eventId: $eventId
    );

    $ticketId = $this->model->create($ticketData);

    if (!$ticketId) {
      throw new \Exception('Erro ao reservar ingresso.', 500);
    }

    return $this->model->getById($ticketId);
  }

  public function expireReservation(int $ticketId) {
    $ticket = $this->model->getById($ticketId);

    if (!$ticket || $ticket['status'] !== 'reserved') {
      throw new \Exception('Ingresso não encontrado ou não está reservado.', 404);
    }

    $this->model->updateStatus($ticketId, 'expired');
  }
}
