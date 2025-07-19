<div class='flex flex-col w-full gap-y-4'>
  <div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg">
    <h1 class="text-2xl font-bold mb-1"><?= htmlspecialchars($event['name']) ?></h1>

    <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($event['description'])) ?></p> 

    <?php if (isset($event['image_url']) && !empty($event['image_url'])): ?>
    <img 
      src="<?= htmlspecialchars($event['image_url']) ?>" 
      alt="<?= htmlspecialchars($event['name']) ?>" 
      class="w-full h-64 object-cover mb-4 rounded-lg"
    >
    <?php endif; ?>

    <div class="mb-4">
      <p class="text-gray-600"><strong>Localização:</strong> <?= htmlspecialchars($event['location']) ?></p>

      <p class="text-gray-600"><strong>Data e Hora de Inicio:</strong> <?= htmlspecialchars($event['start_time']) ?></p>

      <?php if ($event['end_time']): ?>
      <p class="text-gray-600"><strong>Data e Hora de Término:</strong> <?= htmlspecialchars($event['end_time']) ?></p>
      <?php endif; ?>
    </div>

    <div class="mb-4">
      <p class="text-lg font-semibold text-green-600">Preço do Ingresso: $<?= number_format($event['ticket_price'], 2) ?></p>
      <p class="text-gray-600"><strong>Quantidade de Ingressos Disponível:</strong> <?= htmlspecialchars($event['tickets_available']) ?></p>
    </div>


    <?php if($userRole !== 'seller'): ?>
    <form action="/tickets/reserve" method="POST" class="mb-4">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

      <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['id']) ?>">
      <button 
        type="submit" 
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-fit cursor-pointer"
      >
        Comprar Ingresso
      </button>
    </form>
    <?php endif; ?>
  </div>

  <?php require 'partials/seller-event-info.php'; ?>
</div>
