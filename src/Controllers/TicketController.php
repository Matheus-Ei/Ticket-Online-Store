<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\TicketModel;
use App\Utils\SessionUtils;

class TicketController extends AbstractController {
  private $userId = null;
  private $eventModel = null;

  public function __construct() {
    $this->model = new TicketModel();
    $this->userId = SessionUtils::getUserId();
    $this->eventModel = new EventModel();
  }

  public function viewPurchased() {
    $this->ensureLoggedIn('client');

    $purchasedTickets = $this->model->getPurchasedByClient($this->userId);

    $data = [
      'title' => 'Ingressos Comprados',
      'purchasedTickets' => $purchasedTickets,
    ];

    $this->render('resources/views/tickets/view-purchased.php', $data);
  }

  public function viewSpecific($id) {
    $this->ensureLoggedIn('client');

    $ticket = $this->model->get($id);

    $isOwner = $this->model->existsAndIsOwner($id, $this->userId);
    if (!$isOwner) {
      return $this->navigate('/tickets/purchased');
    }

    $data = ['title' => 'Detalhes do Ingresso', 'ticket' => $ticket];
    $this->render('resources/views/tickets/view-specific.php', $data);
  }

  public function buyForm() {
    $this->ensureLoggedIn('client');

    $eventId = $_GET['event_id'] ?? null;

    $event = $this->eventModel->get($eventId);

    $ticketId = null;
    try {
      $ticketId = $this->model->reserve($this->userId, $eventId);
    } catch (\Exception $e) {
      error_log($e->getMessage());
    }

    $data = [
      'title' => 'Comprar Ingresso',
      'event' => $event,
      'ticketId' => $ticketId,
      'createdAt' => $ticketId ? date('Y-m-d H:i:s') : null,
    ];

    $this->render('resources/views/tickets/buy-form.php', $data);
  }

  public function buy() {
    $this->ensureLoggedIn('client');

    $eventId = $_POST['event_id'] ?? null;
    $ticketId = $_POST['ticket_id'] ?? null;

    try {
      $purchasedTicket = $this->model->purchase($this->userId, $eventId, $ticketId);
      return $this->navigate("/tickets/{$purchasedTicket['id']}");
    } catch (\Exception $e) {
      $event = $this->eventModel->get($eventId);

      return $this->throwViewError(
        'resources/views/tickets/buy-form.php', 
        $e, 
        'sidebar', 
        ['event' => $event, 'ticketId' => $ticketId, 'createdAt' => date('Y-m-d H:i:s')]
      );
    }
  }
}
