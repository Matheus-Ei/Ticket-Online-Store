<?php

namespace App\Controllers;

use App\DTOs\EventData;
use App\Models\EventModel;
use App\Utils\SessionUtils;
use App\Validators\EventValidator;

class EventController extends AbstractController {
  private $userRole;
  private $userId;

  public function __construct() {
    $this->model = new EventModel();
    $this->validator = new EventValidator();

    $this->userRole = SessionUtils::getUserRole();
    $this->userId = SessionUtils::getUserId();
  }

  public function index() {
    $data = [
      'title' => 'Eventos',
      'events' => $this->model->getAll(),
    ];

    $this->render('resources/views/events/index.php', $data);
  }

  public function viewSpecific($id) {
    $data = [
      'title' => 'Detalhes do Evento',
      'event' => $this->model->get($id),
    ];

    $this->render('resources/views/events/view-specific.php', $data);
  }

  public function viewPurchased() {
    $this->ensureLoggedIn('client');

    $purchasedEvents = $this->model->getPurchasedByClient($this->userId);

    $data = [
      'title' => 'Eventos Comprados',
      'purchasedEvents' => $purchasedEvents,
    ];

    $this->render('resources/views/events/view-purchased.php', $data);
  }

  private function findAndVerifyOwner(int $eventId) {
    if (!$this->model->existsAndIsOwner($eventId, $this->userId)) {
      $this->navigate('/events/');
    }

    return $this->model->get($eventId);
  }

  public function saveForm() {
    $this->ensureLoggedIn('seller');

    $eventId = $_GET['id'] ?? null;

    // If an event ID is provided, fetch the event details for editing
    if ($eventId) {
      $event = $this->findAndVerifyOwner($eventId);
      $data = ['title' => 'Editar Evento', 'event' => $event];
    } else {
      $data = ['title' => 'Criar Evento'];
    }

    $this->render('resources/views/events/save-form.php', $data);
  }

  public function save() {
    $this->ensureLoggedIn('seller');

    $eventId = $_POST['id'] ?? null;
    $event = null;

    // If an event ID is provided, fetch the event details for editing
    if($eventId) {
      $event = $this->findAndVerifyOwner($eventId);
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
        $this->model->update($eventId, $data);
      } else {
        $this->model->create($data);
      }

      $this->navigate('/events/');
    } catch (\Exception $e) {
      $events = $this->model->getAll();
      $this->throwViewError('resources/views/events/index.php', $e, 'sidebar', ['events' => $events]);
    }
  }

  public function delete($id) {
    $this->ensureLoggedIn('seller');

    // Verify that the event ID is valid and the user is the owner
    $this->findAndVerifyOwner($id);

    try {
      $this->model->delete($id);
      $this->navigate('/events/');
    } catch (\Exception $e) {
      $this->throwViewError('resources/views/events/index.php', $e);
    }
  }
}
