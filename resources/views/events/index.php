<div class="container flex flex-col mx-auto px-4 py-6">
  <h1 class="text-3xl font-bold mb-2">Upcoming Events</h1>

  <p class="text-gray-600 mb-6">
    Here are the upcoming events. Click on "View Details" to see more information about each event.
  </p>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($events as $event) {
    require 'partials/event.php';
    }?>
  </div>

  <?php if ($userRole === 'seller'): ?>
  <a 
    href="/events/save"
    class="w-fit bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition mt-6"
  >Create New Event</a>
  <?php endif; ?>
</div>
