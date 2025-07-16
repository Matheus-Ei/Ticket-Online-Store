<?php

namespace App\Controllers;

use App\DTOs\EventData;
use App\Services\EventService;
use App\Utils\MessageUtils;
use App\Utils\SessionUtils;
use App\Validators\EventValidator;

class EventController extends AbstractController {
  private $userId;

  public function __construct() {
    $this->service = new EventService();
    $this->validator = new EventValidator();

    $this->userId = SessionUtils::getUserId();
  }

  public function index() {
    $data = [
      'title' => 'Eventos',
      'events' => $this->service->getAll(),
    ];

    $this->render('events/index', $data);
  }

  public function viewSpecific($id) {
    $data = [
      'title' => 'Detalhes do Evento',
      'event' => $this->service->get($id),
    ];

    $this->render('events/view-specific', $data);
  }

  public function viewPurchased() {
    $this->checkLogin('client');

    $purchasedEvents = $this->service->getPurchased($this->userId);

    $data = [
      'title' => 'Eventos Comprados',
      'purchasedEvents' => $purchasedEvents,
    ];

    $this->render('events/view-purchased', $data);
  }

  public function saveForm() {
    $this->checkLogin('seller');

    $eventId = $_GET['id'] ?? null;

    // If an event ID is provided, fetch the event details for editing
    if ($eventId) {
      $event = $this->service->getWithOwner($eventId, $this->userId);
      $data = ['title' => 'Editar Evento', 'event' => $event];
    } else {
      $data = ['title' => 'Criar Evento'];
    }

    $this->render('events/save-form', $data);
  }

  public function save() {
    $this->checkLogin('seller');

    $eventId = $_POST['id'] ?? null;
    $event = null;

    // If an event ID is provided, fetch the event details for editing
    if($eventId) {
      $event = $this->service->getWithOwner($eventId, $this->userId);
    }

    // Create or update the event data
    try {
      $data = new EventData(
        name: $_POST['name'] ?? $event['name'] ?? '',
        description: $_POST['description'] ?? $event['description'] ?? '',
        imageUrl: $_POST['image_url'] ?? $event['image_url'] ?? '',
        startTime: new \DateTime($_POST['start_time'] ?? $event['start_time'] ?? 'now'),
        endTime: isset($_POST['end_time']) ? new \DateTime($_POST['end_time']) : null,
        location: $_POST['location'] ?? $event['location'] ?? '',
        ticketPrice: $_POST['ticket_price'] ?? $event['ticket_price'] ?? 0.0,
        ticketQuantity: $_POST['ticket_quantity'] ?? $event['ticket_quantity'] ?? 0,
        createdBy: $this->userId
      );

      // Validate required fields
      $this->validator->validateSave($data);

      // If an event ID is provided, update the existing event
      if ($event) {
        $this->service->update($eventId, $data);
      } else {
        $this->service->create($data);
      }

      MessageUtils::setMessage('success', 'Evento salvo com sucesso!');
      $this->navigate('/events/');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/events/');
    }
  }

  public function delete($id) {
    $this->checkLogin('seller');

    try {
      $this->service->delete($id, $this->userId);

      MessageUtils::setMessage('success', 'Evento excluído com sucesso!');
      $this->navigate('/events/');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/events/');
    }
  }
}
