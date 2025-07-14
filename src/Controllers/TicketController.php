<?php

namespace App\Controllers;

use App\DTOs\TicketData;
use App\Models\TicketModel;
use App\Utils\SessionUtils;

class TicketController extends AbstractController {
  public function __construct() {
    $this->model = new TicketModel();
  }

  public function viewPurchased() {
    $this->ensureLoggedIn('client');

    $clientId = SessionUtils::getUserId();
    $purchasedTickets = $this->model->getPurchasedByClient($clientId);

    $data = [
      'title' => 'Purchased Tickets',
      'purchasedTickets' => $purchasedTickets,
    ];

    $this->renderView('resources/views/tickets/view-purchased.php', $data);
  }

  public function viewSpecific($id) {
    $this->ensureLoggedIn('client');

    $ticket = $this->model->get($id);
    $clientId = SessionUtils::getUserId();

    // Ensure the ticket exists and belongs to the logged-in client
    if (!$ticket || $ticket['client_id'] !== $clientId) {
      return $this->renderView(
        'resources/views/errors/404.php',
        ['message' => 'Ticket not found or does not belong to you.']
      );
    }

    $data = [
      'title' => 'Ticket Details',
      'ticket' => $ticket,
    ];

    $this->renderView('resources/views/tickets/view-specific.php', $data);
  }

  public function buyForm() {
    $this->ensureLoggedIn('client');

    // Creates the ticket with reserved status
    $ticket = new TicketData(
      clientId: (int) SessionUtils::getUserId(),
      eventId: (int)$_GET['event_id'],
      status: 'reserved',
    );

    // Save the reserved ticket to the database
    $ticketId = null;
    try {
      $ticketId = $this->model->create($ticket);
    } catch (\Exception $e) {
      error_log("Error creating reserved ticket: " . $e->getMessage());
    }

    $data = [
      'title' => 'Buy Ticket',
      'ticketId' => $ticketId,
      'createdAt' => $ticketId ? date('Y-m-d H:i:s') : null,
    ];

    $this->renderView('resources/views/tickets/buy-form.php', $data);
  }

  public function buy() {
    $this->ensureLoggedIn('client');

    $ticketId = $_POST['ticket_id'] ?? null;

    // If is not reserved, create a new ticket
    if (!$ticketId) {
      $ticket = new TicketData(
        status: 'reserved',
        clientId: SessionUtils::getUserId(),
        eventId: $_POST['event_id']
      );

      try {
        $ticketId = $this->model->create($ticket);
      } catch (\Exception $e) {
        return $this->throwFormError(
          'Internal error creating ticket',
          'resources/views/tickets/buy-form.php',
          $e,
        );
      }

      return $this->navigate("/tickets/view-specific/$ticketId");
    }

    // Update the ticket status to purchased
    $ticket = $this->model->get($ticketId);

    if (!$ticket) {
      return $this->throwFormError(
        'Ticket not found.',
        'resources/views/tickets/buy-form.php',
      );
    }

    // Verifies the ticket status
    switch ($ticket['status']) {
      case 'purchased':
        return $this->throwFormError(
          'Ticket has already been purchased.',
          'resources/views/tickets/buy-form.php',
        );

      case 'expired':
        return $this->throwFormError(
          'Ticket has expired.',
          'resources/views/tickets/buy-form.php',
        );

      case 'canceled':
        return $this->throwFormError(
          'Ticket has been canceled.',
          'resources/views/tickets/buy-form.php',
        );
    }

    $ticketData = new TicketData(
      status: 'purchased',
      clientId: SessionUtils::getUserId(),
      eventId: $ticket['event_id']
    );

    try {
      $this->model->update($ticketId, $ticketData);
      return $this->navigate("/tickets/$ticketId");
    } catch (\Exception $e) {
      return $this->throwFormError(
        'Internal error updating ticket',
        'resources/views/tickets/buy-form.php',
        $e,
      );
    }
  }
}
