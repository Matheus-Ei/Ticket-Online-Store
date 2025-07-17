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
    $events = $this->service->getAll();

    $data = [
      'title' => 'Eventos',
      'events' => $events,
    ];

    $this->renderView('events/index', $data);
  }

  public function viewSellerEvents() {
    $this->checkLogin('seller');

    try {
      $events = $this->service->getAll($this->userId);
    } catch (\Exception $e) {
      return $this->renderError($e);
    }

    $data = [
      'title' => 'Meus Eventos',
      'events' => $events,
    ];

    $this->renderView('events/view-seller-events', $data);
  }

  public function viewSpecific($id) {
    try {
      $this->validator->validateId($id);

      $event = $this->service->get($id);
      $userRole = SessionUtils::getUserRole();

      if ($userRole === 'seller' && $event['created_by'] === $this->userId) {
        $tickets = $this->service->getTicketsSold($id, $this->userId);
      } 
    } catch (\Exception $e) {
      return $this->renderError($e);
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

    try {
      $purchasedEvents = $this->service->getPurchased($this->userId);
    } catch (\Exception $e) {
      return $this->renderError($e);
    }

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

        $event = $this->service->getWithOwner($eventId, $this->userId);
        $data = ['title' => 'Editar Evento', 'event' => $event];
      } else {
        $data = ['title' => 'Criar Evento'];
      }

      $this->renderView('events/save-form', $data);
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
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
        $event = $this->service->getWithOwner($eventId, $this->userId);
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
        createdBy: $this->userId
      );

      $this->validator->validateSave($data);

      $this->service->save($eventId, $data);

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
      $this->validator->validateId($id, "Event ID");

      $this->service->delete($id, $this->userId);

      MessageUtils::setMessage('success', 'Evento excluído com sucesso!');
      $this->navigate('/events/');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/events/');
    }
  }
}
