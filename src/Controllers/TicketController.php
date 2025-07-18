<?php

namespace App\Controllers;

use App\Services\TicketService;
use App\Services\EventService;
use App\Validators\EventValidator;

class TicketController extends AbstractController {
  public function __construct(
    private TicketService $service,
    private EventService $eventService,
    private EventValidator $validator,
  ) {}

  public function viewPurchased() {
    $this->checkLogin('client');

    $purchasedTickets = $this->service->getPurchased($this->getUserId());

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

      return $this->service->generatePdf($ticketId, $this->getUserId());
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      return $this->navigate('/tickets/purchased');
    }
  }

  public function viewSpecific($id) {
    $this->checkLogin('client');

      $this->validator->validateId($id, 'Ticket ID');

      $ticket = $this->service->get($id, $this->getUserId());

      $data = ['title' => 'Detalhes do Ingresso', 'ticket' => $ticket];
      $this->renderView('tickets/view-specific', $data);
  }

  public function buyForm() {
    $this->checkLogin('client');

    $eventId = $_GET['event_id'] ?? null;
    $ticketId = null;

    try {
      // Gets the event details
      $event = $this->eventService->get($eventId, $this->getUserId());

      // Makes a reservation for the ticket
      $this->validator->validateId($eventId, 'Event ID');

      $ticket = $this->service->reserve($this->getUserId(), $eventId);

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
      $this->setMessage('warning', $e->getMessage());
      $this->navigate("/events/{$eventId}");
    }
  }

  public function expireReservation($id) {
    $this->checkLogin('client');

    try {
      $this->validator->validateId($id, 'Ticket ID');

      $this->service->expireReservation($id);
      $this->setMessage('warning', 'Reserva expirada. Por favor, tente novamente.');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
    }
  }

  public function buy() {
    $this->checkLogin('client');

    $eventId = $_POST['event_id'] ?? null;
    $ticketId = $_POST['ticket_id'] ?? null;

    try {
      $this->validator->validateId($eventId, 'Event ID');
      $this->validator->validateId($ticketId, 'Ticket ID');

      $ticketId = $this->service->purchase($this->getUserId(), $eventId, $ticketId);

      $this->setMessage('success', 'Ingresso comprado com sucesso!');
      return $this->navigate("/tickets/{$ticketId}");
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/events');
    }
  }
}
