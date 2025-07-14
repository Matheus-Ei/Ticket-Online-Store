<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg mt-6">
  <h1 class="text-3xl font-semibold text-gray-800 mb-1">Ticket Details</h1>

  <p class="text-gray-600 mb-6">Here are the details for your ticket:</p>

  <?php if (!$ticket): ?>
  <p class="text-red-600">Ticket not found.</p>
  <?php else: ?>
  <div class="bg-white border border-gray-200 rounded-lg p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ticket ID: <?= htmlspecialchars($ticket['id']) ?></h2>
    <p class="text-gray-600 mb-2"><strong>Event ID:</strong> <?= htmlspecialchars($ticket['event_id']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Created At:</strong> <?= htmlspecialchars($ticket['created_at']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Client ID:</strong> <?= htmlspecialchars($ticket['client_id']) ?></p>
  </div>
  <?php endif; ?>
  <div class="mt-6">
    <a href="/tickets/purchased" class="text-blue-600 hover:underline">Back to Purchased Tickets</a>
  </div>
</div>
