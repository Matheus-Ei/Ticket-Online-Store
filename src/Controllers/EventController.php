<?php

namespace App\Controllers;

use App\DTOs\EventData;
use App\Models\EventModel;
use App\Utils\SessionUtils;

class EventController extends AbstractController {
  public function __construct() {
    $this->model = new EventModel();
  }

  public function index() {
    $events = $this->model->getAll();

    $data = [
      'title' => 'Events',
      'events' => $events,
      'userRole' => SessionUtils::getUserRole(),
    ];

    $this->renderView('resources/views/events/index.php', $data);
  }

  public function viewSpecific($id) {
    $event = $this->model->get($id);

    $event['tickets_available'] = $this->model->getTicketsAvailable($id);

    $data = [
      'title' => 'Event Details',
      'event' => $event,
    ];

    $this->renderView('resources/views/events/view-specific.php', $data);
  }

  public function viewPurchased() {
    $this->ensureLoggedIn('client');

    // Fetch the purchased events for the logged-in client
    $userId = SessionUtils::getUserId();
    $purchasedEvents = $this->model->getPurchasedByClient($userId);

    $data = [
      'title' => 'Purchased Events',
      'purchasedEvents' => $purchasedEvents,
    ];

    $this->renderView('resources/views/events/view-purchased.php', $data);
  }

  public function saveForm() {
    $this->ensureLoggedIn('seller');

    $eventId = $_GET['id'] ?? null;

    // If an event ID is provided, fetch the event details for editing
    if ($eventId) {
      $event = $this->model->getById($eventId);

      // Check if the event exists and if the user is the owner
      if (!$event || $event['created_by'] !== SessionUtils::getUserId()) {
        $this->navigate('/events/');
      }

      $data = [
        'title' => 'Edit Event',
        'event' => $event,
      ];
    } else {
      $data = [
        'title' => 'Create Event',
      ];
    }

    $this->renderView('resources/views/events/save-form.php', $data);
  }

  public function save() {
    $this->ensureLoggedIn('seller');

    $eventId = $_POST['id'] ?? null;
    $event = null;

    // If an event ID is provided, fetch the event details for editing
    if($eventId) {
      $event = $this->model->getById($eventId);

      // Check if the event exists and if the user is the owner
      if (!$event || $event['created_by'] !== SessionUtils::getUserId()) {
        $this->navigate('/events/');
      }
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
      createdBy: SessionUtils::getUserId()
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
    if (!$eventId) {
      $this->navigate('/events/');
    }

    $event = $this->model->getById($eventId);

    // Check if the event exists and if the user is the owner
    if (!$event || $event['created_by'] !== SessionUtils::getUserId()) {
      $this->navigate('/events/');
    }

    $this->model->delete($eventId);
  }
}
