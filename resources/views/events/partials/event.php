<div class="bg-white rounded-lg border border-gray-200 p-6">
  <img 
    src="<?= htmlspecialchars($event['image_url'])?>" 
    alt="<?=htmlspecialchars($event['name'])?>" 
    class="w-full h-48 object-cover rounded-t-lg mb-4"
  >

  <h2 class="text-xl font-semibold mb-2"><?=htmlspecialchars($event['name'])?></h2>

  <p class="text-gray-700 mb-4"><?=htmlspecialchars($event['description'])?></p>

  <p class="text-gray-500 mb-2">Start: <?= date('F j, Y, g:i A', strtotime($event['start_time'])); ?></p>

  <?php if ($event['end_time']): ?>
  <p class="text-gray-500 mb-4">End: <?=date('F j, Y, g:i A', strtotime($event['end_time']))?></p>
  <?php endif; ?>

  <p class="text-gray-500 mb-4">Location: <?=htmlspecialchars($event['location'])?></p>

  <p class="text-green-600 font-bold mb-4">
    <?php if ($event['ticket_price'] > 0): ?>
    $<?=number_format($event['ticket_price'], 2)?> per ticket
    <?php else: ?>
    Free Entry
    <?php endif; ?>
  </p>

  <p class="text-gray-500 mb-4">Tickets Available: <?=htmlspecialchars($event['ticket_quantity'])?></p>

  <a 
    href="/events/<?=$event['id']?>" 
    class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
  >
    View Details
  </a>
</div>

