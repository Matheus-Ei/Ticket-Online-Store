<div class="flex flex-col container mx-auto">
  <h1 class="text-2xl font-bold mb-1">Comprar um Ingresso</h1>

  <p class="text-gray-700 mb-4">Por favor, confirme que deseja realmente comprar este ingresso.</p>

  <form action="/tickets/buy" method="POST" class="space-y-4">
    <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['id']) ?>">

    <?php if (isset($ticketId)): ?>
    <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticketId) ?>">
    <?php endif; ?>

    <div>
      <label for="name" class="block text-gray-700 mb-2">Nome</label>
      <input 
        type="text" 
        id="name" 
        placeholder="Seu nome completo"
        name="name" 
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
    </div>

    <div>
      <label for="email" class="block text-gray-700 mb-2">Email</label>
      <input 
        type="email" 
        id="email" 
        placeholder="Seu email para receber a confirmação"
        name="email" 
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-fit cursor-pointer">
      Comprar Ingresso
    </button>
  </form>
</div>
