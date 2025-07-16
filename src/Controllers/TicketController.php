<?php

namespace App\Controllers;

use App\Services\TicketService;
use App\Services\EventService;
use App\Utils\MessageUtils;
use App\Utils\SessionUtils;

class TicketController extends AbstractController {
  private $userId = null;
  private $eventService;

  public function __construct() {
    $this->service = new TicketService();
    $this->userId = SessionUtils::getUserId();
    $this->eventService = new EventService();
  }

  public function viewPurchased() {
    $this->checkLogin('client');

    $purchasedTickets = $this->service->getPurchased($this->userId);

    $data = [
      'title' => 'Ingressos Comprados',
      'purchasedTickets' => $purchasedTickets,
    ];

    $this->render('tickets/view-purchased', $data);
  }

  public function viewSpecific($id) {
    $this->checkLogin('client');

    try {
      $ticket = $this->service->get($id, $this->userId);

      $data = ['title' => 'Detalhes do Ingresso', 'ticket' => $ticket];
      $this->render('tickets/view-specific', $data);
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      return $this->navigate('/tickets/purchased');
    }
  }

  public function buyForm() {
    $this->checkLogin('client');

    // TODO: Add validator for event ID
    $eventId = $_GET['event_id'] ?? null;

    $event = $this->eventService->get($eventId, $this->userId);

    // TODO: Check if there are tickets available for the event

    $ticketId = null;
    try {
      $ticketId = $this->service->reserve($this->userId, $eventId);
      $ticket = $this->service->get($ticketId, $this->userId);

      $_SESSION['reservation_time'] = $ticket['created_at'] ?? time();
      $reservationTime = $_SESSION['reservation_time'];
    } catch (\Exception $e) {
      MessageUtils::setMessage('warning', $e->getMessage());
    }

    $data = [
      'title' => 'Comprar Ingresso',
      'event' => $event,
      'ticketId' => $ticketId,
      'reservationTime' => $reservationTime ?? null,
    ];

    $this->render('tickets/buy-form', $data);
  }

  public function expireReservation($id) {
    $this->checkLogin('client');

    // TODO: Create the id validator method on AbstractValidator
    if (!$id || !is_numeric($id)) {
      MessageUtils::setMessage('error', 'ID do ingresso inválido.');
      return $this->navigate('/tickets/buy');
    }

    try {
      $this->service->expireReservation($id);
      MessageUtils::setMessage('warning', 'Reserva expirada. Por favor, tente novamente.');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
    }
  }

  public function buy() {
    $this->checkLogin('client');

    $eventId = $_POST['event_id'] ?? null;
    $ticketId = $_POST['ticket_id'] ?? null;

    try {
      // TODO: Add validators for the event ID and ticket ID
      $ticketId = $this->service->purchase($this->userId, $eventId, $ticketId);

      MessageUtils::setMessage('success', 'Ingresso comprado com sucesso!');
      return $this->navigate("/tickets/{$ticketId}");
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/tickets/buy');
    }
  }
}
