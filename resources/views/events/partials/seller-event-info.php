<?php if($userRole === 'seller' && $event['created_by'] === $userId): ?>
<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg">
  <h2 class="text-xl font-bold mb-1">Gerenciar Evento</h2>
  <p class="text-gray-600 mb-4">Você pode editar ou excluir este evento, ou gerenciar os ingressos vendidos.</p>

  <div class='flex gap-x-4'>
    <a 
      href="/events/save?id=<?= htmlspecialchars($event['id']) ?>" 
      class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
    >
      Editar Evento
    </a>

    <form action="/events/delete" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
      <input type="hidden" name="id" value="<?= htmlspecialchars($event['id']) ?>">

      <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 cursor-pointer">
        Excluir Evento
      </button>
    </form>
  </div>
</div>


<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg">
  <h2 class="text-xl font-bold">Ingressos Vendidos</h2>

  <p class="text-gray-600 mb-4">Gerencie os ingressos vendidos para este evento.</p>

  <?php foreach ($tickets as $ticket): ?>
  <div class="mb-4 p-4 border border-gray-300 rounded-lg">
    <p><strong>Comprador:</strong> <?= htmlspecialchars($ticket['client_name']) ?></p>

    <p><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p>

    <p><strong>Data de Compra:</strong> <?= htmlspecialchars($ticket['created_at']) ?></p>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

