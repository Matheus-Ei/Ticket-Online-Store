<div class="container flex flex-col mx-auto px-4 py-6">
  <h1 class="text-3xl font-bold mb-2">Purchased Events</h1>

  <p class="text-gray-600 mb-6">
    Here are the events you have purchased tickets for. Click on "View Details" to see more information about each event.
  </p>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($purchasedEvents as $event) {
    require 'partials/event.php';
    }?>
  </div>
</div>
