<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg mt-6">
  <h1 class="text-3xl font-semibold text-gray-800 mb-1">Purchased Tickets</h1>
  <p class="text-gray-600 mb-6">Here are the tickets you have purchased:</p>

  <?php if (empty($purchasedTickets)): ?>
  <p class="text-gray-600">You have not purchased any tickets yet.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Ticket ID</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Event ID</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Status</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($purchasedTickets as $ticket) {
          require 'partials/ticket-row.php';
        }?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
