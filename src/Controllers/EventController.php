<?php

namespace App\Controllers;

use App\DTOs\EventData;
use App\Services\EventService;
use App\Validators\EventValidator;

class EventController extends AbstractController {
  public function __construct(
    private EventService $service,
    private EventValidator $validator,
  ) {}

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

      if ($userRole === 'seller' && $event['created_by'] === $this->getUserId()) {
        $tickets = $this->service->getTicketsSold($id, $this->getUserId());
      } 

    $data = [
      'title' => 'Detalhes do Evento',
      'event' => $event,
      'tickets' => $tickets ?? [],
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

    $eventId = $_GET['id'] ?? null;

    try {
      // If an event ID is provided, fetch the event details for editing
      if ($eventId) {
        $this->validator->validateId($eventId);

        $event = $this->service->getWithOwner($eventId, $this->getUserId());
        $data = ['title' => 'Editar Evento', 'event' => $event];
      } else {
        $data = ['title' => 'Criar Evento'];
      }

      $this->renderView('events/save-form', $data);
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/events');
    }
  }

  public function save() {
    $this->checkLogin('seller');

    $eventId = $_POST['id'] ?? null;
    $event = null;

    try {
      // If an event ID is provided, fetch the event details for editing
      if($eventId) {
        $this->validator->validateId($eventId, "Event ID");
        $event = $this->service->getWithOwner($eventId, $this->getUserId());
      }

      // Create or update the event data
      $data = new EventData(
        name: $_POST['name'] ?? $event['name'] ?? '',
        description: $_POST['description'] ?? $event['description'] ?? '',
        imageUrl: $_POST['image_url'] ?? $event['image_url'] ?? '',
        startTime: new \DateTime($_POST['start_time'] ?? $event['start_time'] ?? 'now'),
        endTime: !empty($_POST['end_time']) ? new \DateTime($_POST['end_time']) : null,
        location: $_POST['location'] ?? $event['location'] ?? '',
        ticketPrice: $_POST['ticket_price'] ?? $event['ticket_price'] ?? 0.0,
        ticketQuantity: $_POST['ticket_quantity'] ?? $event['ticket_quantity'] ?? 0,
        createdBy: $this->getUserId()
      );

      $this->validator->validateSave($data);

      $this->service->save($eventId, $data);

      $this->setMessage('success', 'Evento salvo com sucesso!');
      $this->navigate('/events/');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/events/');
    }
  }

  public function delete($id) {
    $this->checkLogin('seller');

    try {
      $this->validator->validateId($id, "Event ID");

      $this->service->delete($id, $this->getUserId());

      $this->setMessage('success', 'Evento excluído com sucesso!');
      $this->navigate('/events/');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/events/');
    }
  }
}
