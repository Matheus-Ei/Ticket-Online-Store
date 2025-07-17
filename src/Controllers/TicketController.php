<?php

namespace App\Controllers;

use App\Services\TicketService;
use App\Services\EventService;
use App\Utils\MessageUtils;
use App\Utils\SessionUtils;
use App\Validators\EventValidator;

class TicketController extends AbstractController {
  private $userId = null;
  private $eventService;

  public function __construct() {
    $this->service = new TicketService();
    $this->userId = SessionUtils::getUserId();
    $this->eventService = new EventService();
    $this->validator = new EventValidator();
  }

  public function viewPurchased() {
    $this->checkLogin('client');

    try {
      $purchasedTickets = $this->service->getPurchased($this->userId);
    } catch (\Exception $e) {
      return $this->renderError($e);
    }

    $data = [
      'title' => 'Ingressos Comprados',
      'purchasedTickets' => $purchasedTickets,
    ];

    $this->renderView('tickets/view-purchased', $data);
  }

  public function generatePdf($ticketId) {
    $this->checkLogin('client');

    try {
      $this->validator->validateId($ticketId, 'ID do Ingresso');

      return $this->service->generatePdf($ticketId, $this->userId);
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      return $this->navigate('/tickets/purchased');
    }
  }

  public function viewSpecific($id) {
    $this->checkLogin('client');

    try {
      $this->validator->validateId($id, 'Ticket ID');

      $ticket = $this->service->get($id, $this->userId);

      $data = ['title' => 'Detalhes do Ingresso', 'ticket' => $ticket];
      $this->renderView('tickets/view-specific', $data);
    } catch (\Exception $e) {
      $this->renderError($e);
    }
  }

  public function buyForm() {
    $this->checkLogin('client');

    $eventId = $_GET['event_id'] ?? null;
    $ticketId = null;

    try {
      // Gets the event details
      $event = $this->eventService->get($eventId, $this->userId);

      // Makes a reservation for the ticket
      $this->validator->validateId($eventId, 'Event ID');

      $ticket = $this->service->reserve($this->userId, $eventId);

      // Store reservation time in session
      $_SESSION['reservation_time'] = $ticket['created_at'];
      $reservationTime = $_SESSION['reservation_time'];

      $data = [
        'title' => 'Comprar Ingresso',
        'event' => $event,
        'ticketId' => $ticket['id'],
        'reservationTime' => $reservationTime ?? null,
      ];

      $this->renderView('tickets/buy-form', $data);
    } catch (\Exception $e) {
      MessageUtils::setMessage('warning', $e->getMessage());
      $this->navigate("/events/{$eventId}");
    }
  }

  public function expireReservation($id) {
    $this->checkLogin('client');

    try {
      $this->validator->validateId($id, 'Ticket ID');

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
      $this->validator->validateId($eventId, 'Event ID');
      $this->validator->validateId($ticketId, 'Ticket ID');

      $ticketId = $this->service->purchase($this->userId, $eventId, $ticketId);

      MessageUtils::setMessage('success', 'Ingresso comprado com sucesso!');
      return $this->navigate("/tickets/{$ticketId}");
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/events');
    }
  }
}
