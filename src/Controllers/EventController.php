<?php

namespace App\Controllers;

class EventController extends AbstractController {
  public function index() {
    // GET Render the list of events
    // Permissions: Public

    $data = [
      'title' => 'Events',
    ];

    $this->renderView('resources/views/events/index.php', $data);
  }

  public function viewSpecific() {
    // GET Render the details of a specific event
    // Permissions: Public
    
    $data = [
      'title' => 'Event Details',
    ];

    $this->renderView('resources/views/events/view-specific.php', $data);
  }

  public function viewPurchased() {
    // GET Render the list of purchased events of the client
    // Permissions: Owner client

    $data = [
      'title' => 'Purchased Events',
    ];

    $this->renderView('resources/views/events/view-purchased.php', $data);
  }

  public function saveForm() {
    // GET Render the form to edit or create an event
    // Permissions: 
      // If create: Logged in user
      // If edit: Owner user
    
    $data = [
      'title' => 'Create/Edit Event',
    ];

    $this->renderView('resources/views/events/save-form.php', $data);
  }

  public function save() {
    // POST Handle the logic to edit or create an event
    // Permissions: 
      // If create: Logged in user
      // If edit: Owner user 
  }

  public function delete() {
    // POST Handle the logic to delete an event
    // Permissions: Owner user
  }
}
