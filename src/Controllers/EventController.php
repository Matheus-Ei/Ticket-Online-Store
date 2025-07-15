<?php

namespace App\Controllers;

use App\DTOs\EventData;
use App\Models\EventModel;
use App\Utils\SessionUtils;

class EventController extends AbstractController {
  private $userRole;
  private $userId;

  public function __construct() {
    $this->model = new EventModel();

    $this->userRole = SessionUtils::getUserRole();
    $this->userId = SessionUtils::getUserId();
  }

  public function index() {
    $data = [
      'title' => 'Events',
      'events' => $this->model->getAll(),
      'userRole' => $this->userRole,
    ];

    $this->renderWithSidebar('resources/views/events/index.php', $data);
  }

  public function viewSpecific($id) {
    $data = [
      'title' => 'Event Details',
      'event' => $this->model->get($id),
    ];

    $this->renderWithSidebar('resources/views/events/view-specific.php', $data);
  }

  public function viewPurchased() {
    $this->ensureLoggedIn('client');

    $purchasedEvents = $this->model->getPurchasedByClient($this->userId);

    $data = [
      'title' => 'Purchased Events',
      'purchasedEvents' => $purchasedEvents,
    ];

    $this->renderWithSidebar('resources/views/events/view-purchased.php', $data);
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
      $data = ['title' => 'Edit Event', 'event' => $event];
    } else {
      $data = ['title' => 'Create Event'];
    }

    $this->renderWithSidebar('resources/views/events/save-form.php', $data);
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

    // If an event ID is provided, update the existing event
    if ($event) {
      $this->model->update($eventId, $data);
    } else {
      $this->model->create($data);
    }

    $this->navigate('/events/');
  }

  public function delete() {
    $this->ensureLoggedIn('seller');

    // Ensure the event ID is provided
    $eventId = $_POST['id'] ?? null;

    // Verify that the event ID is valid and the user is the owner
    $isOwner = $this->model->existsAndIsOwner($eventId, $this->userId);
    if(!$isOwner) {
      $this->navigate('/events/');
    };

    $this->model->delete($eventId);
    $this->navigate('/events/');
  }
}
