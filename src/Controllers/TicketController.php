<?php

namespace App\Controllers;

use App\Services\TicketService;
use App\Services\EventService;
use App\Validators\TicketValidator;
use Core\Request;
use Core\Session;

class TicketController extends AbstractController {
  public function __construct(
    private Session $session,
    private Request $request,
    private TicketService $service,
    private TicketValidator $validator,
    private EventService $eventService,
  ) {
    parent::__construct($session, $request);
  }

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

    $this->validator->validateId($ticketId, 'ID do Ingresso');

    return $this->service->generatePdf($ticketId, $this->getUserId());
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

    $eventId = $this->request->get('event_id');
    $ticketId = null;

    // Gets the event details
    $event = $this->eventService->get($eventId, $this->getUserId());

    // Makes a reservation for the ticket
    $this->validator->validateId($eventId, 'Event ID');

    $ticket = $this->service->reserve($this->getUserId(), $eventId);

    // Store reservation time in session
    $reservationTime = $ticket['created_at'];
    $this->session->set('reservation_time', $reservationTime);

    $data = [
      'title' => 'Comprar Ingresso',
      'event' => $event,
      'ticketId' => $ticket['id'],
      'reservationTime' => $reservationTime ?? null,
    ];

    $this->renderView('tickets/buy-form', $data);
  }

  public function expireReservation($id) {
    $this->checkLogin('client');

    $this->validator->validateId($id, 'Ticket ID');

    $this->service->expireReservation($id);
    $this->setMessage('warning', 'Reserva expirada. Por favor, tente novamente.');
  }

  public function buy() {
    $this->checkLogin('client');

    $eventId = $this->request->post('event_id');
    $ticketId = $this->request->post('ticket_id');

    $this->validator->validateId($eventId, 'Event ID');
    $this->validator->validateId($ticketId, 'Ticket ID');

    $ticketId = $this->service->purchase($this->getUserId(), $eventId, $ticketId);

    $this->setMessage('success', 'Ingresso comprado com sucesso!');
    return $this->navigate("/tickets/{$ticketId}");
  }
}
