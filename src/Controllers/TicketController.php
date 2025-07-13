<?php

namespace App\Controllers;

class TicketController extends AbstractController {
  public function index() {
    // GET Render the list of tickets view
    // Permissions: Public
    
    $data = [
      'title' => 'Tickets',
    ];

    $this->renderView('resources/views/tickets/index.php', $data);
  }

  public function viewPurchased() {
    // GET Render the view for purchased tickets of the client
    // Permissions: Owner client

    $data = [
      'title' => 'Purchased Tickets',
    ];

    $this->renderView('resources/views/tickets/view-purchased.php', $data);
  }

  public function viewSpecific() {
    // GET Render the view for a specific ticket that the client has purchased
    // Permissions: Owner client

    $data = [
      'title' => 'Ticket Details',
    ];

    $this->renderView('resources/views/tickets/view-specific.php', $data);
  }

  public function buyForm() {
    // GET Render the form to buy a ticket
    // Permissions: Logged in client

    $data = [
      'title' => 'Buy Ticket',
    ];

    $this->renderView('resources/views/tickets/buy-form.php', $data);
  }

  public function buy() {
    // POST Handle the logic to buy a ticket
    // Permissions: Logged in client
  }

  public function cancel() {
    // POST Handle the logic to cancel a purchased ticket
    // Permissions: Owner client
  }
}
