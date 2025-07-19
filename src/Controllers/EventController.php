<?php

namespace App\Controllers;

use App\DTOs\EventData;
use App\Services\EventService;
use App\Validators\EventValidator;
use Core\Request;
use Core\Session;

class EventController extends AbstractController {
  public function __construct(
    private Session $session,
    private Request $request,
    private EventService $service,
    private EventValidator $validator,
  ) {
    parent::__construct($session, $request);
  }

  public function index() {
    $events = $this->service->getAll();

    $data = [
      'title' => 'Eventos',
      'events' => $events,
    ];

    $this->renderView('events/index', $data);
  }

  public function viewSellerEvents() {
    $this->checkLogin('seller');

    $events = $this->service->getAll($this->getUserId());

    $data = [
      'title' => 'Meus Eventos',
      'events' => $events,
    ];

    $this->renderView('events/view-seller-events', $data);
  }

  public function viewSpecific($id) {
    $this->validator->validateId($id);

    $event = $this->service->get($id);

    $userRole = $this->getUserRole();
    $userId = $this->getUserId();

    $tickets = $this->service->getTicketsSold($id, $userId, $userRole);

    $data = [
      'title' => 'Detalhes do Evento',
      'event' => $event,
      'tickets' => $tickets,
    ];

    $this->renderView('events/view-specific', $data);
  }

  public function viewPurchased() {
    $this->checkLogin('client');

    $purchasedEvents = $this->service->getPurchased($this->getUserId());

    $data = [
      'title' => 'Eventos Comprados',
      'purchasedEvents' => $purchasedEvents,
    ];

    $this->renderView('events/view-purchased', $data);
  }

  public function saveForm() {
    $this->checkLogin('seller');

    $eventId = $this->request->get('id');

    // If an event ID is provided, fetch the event details for editing
    if ($eventId) {
      $this->validator->validateId($eventId);

      $event = $this->service->getWithOwner($eventId, $this->getUserId());
      $data = ['title' => 'Editar Evento', 'event' => $event];
    } else {
      $data = ['title' => 'Criar Evento'];
    }

    $this->renderView('events/save-form', $data);
  }

  public function save() {
    $this->checkLogin('seller');

    $eventId = $this->request->post('id') ?? null;
    $event = null;

    // If an event ID is provided, fetch the event details for editing
    if($eventId) {
      $this->validator->validateId($eventId, "Event ID");
      $event = $this->service->getWithOwner($eventId, $this->getUserId());
    }

    // Create or update the event data
    $data = new EventData(
      name: $this->request->post('name') ?? $event['name'] ?? '',
      description: $this->request->post('description') ?? $event['description'] ?? '',
      imageUrl: $this->request->post('image_url') ?? $event['image_url'] ?? '',
      startTime: new \DateTime($this->request->post('start_time') ?? $event['start_time'] ?? 'now'),
      endTime: !empty($this->request->post('end_time')) ? new \DateTime($this->request->post('end_time')) : null,
      location: $this->request->post('location') ?? $event['location'] ?? '',
      ticketPrice: $this->request->post('ticket_price') ?? $event['ticket_price'] ?? 0.0,
      ticketQuantity: $this->request->post('ticket_quantity') ?? $event['ticket_quantity'] ?? 0,
      createdBy: $this->getUserId()
    );

    $this->validator->validateSave($data);

    $this->service->save($eventId, $data);

    $this->setMessage('success', 'Evento salvo com sucesso!');
    $this->navigate('/events/');
  }

  public function delete($id) {
    $this->checkLogin('seller');

    $this->validator->validateId($id, "Event ID");

    $this->service->delete($id, $this->getUserId());

    $this->setMessage('success', 'Evento excluído com sucesso!');
    $this->navigate('/events/');
  }
}
