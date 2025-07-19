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

  public function reserve() {
    $this->checkLogin('client');

    $this->validator->validateCsrfToken(
      $this->session->get('csrf_token'),
      $this->request->post('csrf_token')
    );

    $eventId = $this->request->post('event_id');
    $this->validator->validateId($eventId, 'Event ID');

    $ticket = $this->service->reserve($this->getUserId(), $eventId);

    $reservationTime = $ticket['created_at'];
    $this->session->set('reservation_time', $reservationTime);

    $this->navigate("/tickets/buy?event_id={$eventId}");
  }

  public function buyForm() {
    $this->checkLogin('client');

    $eventId = $this->request->get('event_id');

    // Gets the event details
    $event = $this->eventService->get($eventId, $this->getUserId());

    $reservationTime = $this->session->get('reservation_time');

    $data = [
      'title' => 'Comprar Ingresso',
      'event' => $event,
      'reservationTime' => $reservationTime,
      'csrf_token' => $this->session->get('csrf_token'),
    ];

    $this->renderView('tickets/buy-form', $data);
  }

  public function expireReservation() {
    $this->checkLogin('client');

    $this->validator->validateCsrfToken(
      $this->session->get('csrf_token'),
      $this->request->json('csrf_token')
    );

    $eventId = $this->request->json('event_id');

    $this->service->expireReservation($this->getUserId(), $eventId);
    $this->setMessage('warning', 'Reserva expirada. Por favor, tente novamente.');
  }

  public function buy() {
    $this->checkLogin('client');

    $this->validator->validateCsrfToken(
      $this->session->get('csrf_token'),
      $this->request->post('csrf_token')
    );

    $eventId = $this->request->post('event_id');
    $this->validator->validateId($eventId, 'Event ID');

    $ticketId = $this->service->purchase($this->getUserId(), $eventId);

    $this->setMessage('success', 'Ingresso comprado com sucesso!');
    return $this->navigate("/tickets/{$ticketId}");
  }
}
