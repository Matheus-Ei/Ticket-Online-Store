<div class="flex flex-col container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-1"><?= htmlspecialchars($event['name']) ?></h1>

  <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($event['description'])) ?></p> 

  <img 
    src="<?= htmlspecialchars($event['image_url']) ?>" 
    alt="<?= htmlspecialchars($event['name']) ?>" 
    class="w-full h-64 object-cover mb-4 rounded-lg"
  >

  <div class="mb-4">
    <p class="text-gray-600">Location: <?= htmlspecialchars($event['location']) ?></p>

    <p class="text-gray-600">Start Time: <?= htmlspecialchars($event['start_time']) ?></p>

    <?php if ($event['end_time']): ?>
    <p class="text-gray-600">End Time: <?= htmlspecialchars($event['end_time']) ?></p>
    <?php endif; ?>
  </div>

  <div class="mb-4">
    <p class="text-lg font-semibold">Ticket Price: $<?= number_format($event['ticket_price'], 2) ?></p>
    <p class="text-gray-600">Tickets Available: <?= htmlspecialchars($event['tickets_available']) ?></p>
  </div>

  <?php if ($event['ticket_quantity'] > 0): ?>
  <a 
    href='/tickets/buy?event_id=<?= htmlspecialchars($event['id']) ?>'
    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-fit cursor-pointer"
  >
    Purchase Ticket
  </a>
  <?php else: ?>

  <p class="text-red-500 mt-4">Tickets are sold out.</p>
  <?php endif; ?>
</div>
