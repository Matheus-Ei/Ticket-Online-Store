<?php

namespace App\Controllers;

use App\Models\EventModel;

class EventController extends AbstractController {
  public function __construct() {
    $this->model = new EventModel();
  }

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

    $this->ensureLoggedIn('client');

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

    $this->ensureLoggedIn('user');

    // Verify if the user is the owner of the event if editing

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

    $this->ensureLoggedIn('user');
  }

  public function delete() {
    // POST Handle the logic to delete an event
    // Permissions: Owner user

    $this->ensureLoggedIn('user');
  }
}
