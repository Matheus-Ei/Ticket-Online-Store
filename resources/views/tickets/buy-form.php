<div class="flex flex-col container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-1">Purchase Ticket</h1>

  <p class="text-gray-700 mb-4">Please confirm your details to purchase a ticket for the event.</p>

  <form action="/tickets/buy" method="POST" class="space-y-4">
    <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['id']) ?>">

    <?php if (isset($ticketId)): ?>
      <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticketId) ?>">
    <?php endif; ?>

    <div>
      <label for="name" class="block text-gray-700 mb-2">Name</label>
      <input 
        type="text" 
        id="name" 
        name="name" 
        required 
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
    </div>

    <div>
      <label for="email" class="block text-gray-700 mb-2">Email</label>
      <input 
        type="email" 
        id="email" 
        name="email" 
        required 
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
    </div>

    <div>
      <label for="quantity" class="block text-gray-700 mb-2">Quantity</label>
      <input 
        type="number" 
        id="quantity" 
        name="quantity" 
        min="1" 
        max="<?= htmlspecialchars($event['tickets_available']) ?>" 
        value="1" 
        required 
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-fit cursor-pointer">
      Purchase Ticket
    </button>
  </form>
</div>
