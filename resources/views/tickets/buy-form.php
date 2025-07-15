<div class="flex flex-col container mx-auto">
  <h1 class="text-2xl font-bold mb-1">Comprar um Ingresso</h1>

  <p class="text-gray-700 mb-4">Por favor, confirme que deseja realmente comprar este ingresso.</p>

  <div class="bg-white p-4 rounded shadow mb-4">
    <h1 class="text-2xl font-semibold">Detalhes do Evento</h1>  
    <p class="text-gray-600 mb-4">Você está comprando um ingresso para o evento:</p>

    <h2 class="text-xl font-semibold"><?= htmlspecialchars($event['name']) ?></h2>
    <p class="text-gray-600 mb-2"><?= htmlspecialchars($event['description']) ?></p>

    <?php if (isset($event['image_url']) && !empty($event['image_url'])): ?>
    <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="Imagem do Evento" class="w-full h-64 object-cover rounded mb-4">
    <?php endif; ?>

    <p class="text-gray-600 mb-1"><strong>Data:</strong> <?= htmlspecialchars($event['start_time']) ?></p>
    <p class="text-gray-600 mb-1"><strong>Local:</strong> <?= htmlspecialchars($event['location']) ?></p>
    <p class="text-gray-600 mb-1"><strong>Preço:</strong> R$ <?= number_format($event['ticket_price'], 2, ',', '.') ?></p>
  </div>

  <form action="/tickets/buy" method="POST" class="space-y-4">
    <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['id']) ?>">

    <?php if (isset($ticketId)): ?>
    <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticketId) ?>">
    <?php endif; ?>

    <div>
      <label for="email" class="block text-gray-700 mb-2">Email</label>
      <input 
        type="email" 
        id="email" 
        placeholder="Seu email para receber a confirmação"
        name="email" 
        class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 outline-none bg-white"
      >
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-fit cursor-pointer">
      Comprar Ingresso
    </button>
  </form>
</div>
