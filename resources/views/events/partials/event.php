<div class="bg-white rounded-lg border border-gray-200 p-6 h-fit">
  <?php if (!empty($event['image_url'])): ?>
  <img 
    src="<?= htmlspecialchars($event['image_url'])?>" 
    alt="<?=htmlspecialchars($event['name'])?>" 
    class="w-full h-48 object-cover rounded-t-lg mb-4"
  >
  <?php endif?>

  <h2 class="text-xl font-semibold mb-2"><?=htmlspecialchars($event['name'])?></h2>

  <p class="text-gray-700 mb-4"><?=htmlspecialchars($event['description'])?></p>

  <p class="text-gray-500"><strong>Inicio:</strong> <?= date('F j, Y, g:i A', strtotime($event['start_time'])); ?></p>

  <?php if ($event['end_time']): ?>
  <p class="text-gray-500"><strong>Fim:</strong> <?=date('F j, Y, g:i A', strtotime($event['end_time']))?></p>
  <?php endif; ?>

  <p class="text-gray-500 mb-4"><strong>Localização:</strong> <?=htmlspecialchars($event['location'])?></p>

  <p class="text-green-600 font-bold mb-4">
    <?php if ($event['ticket_price'] > 0): ?>
    $<?=number_format($event['ticket_price'], 2)?> por ingresso
    <?php else: ?>
    Entrada gratuita
    <?php endif; ?>
  </p>

  <p class="text-gray-500"><strong>Ingressos disponíveis:</strong> <?=htmlspecialchars($event['tickets_available'])?></p>
  <p class="text-gray-500 mb-4"><strong>Ingressos totais:</strong> <?=htmlspecialchars($event['ticket_quantity'])?></p>

  <a 
    href="/events/<?=$event['id']?>" 
    class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
  >
    Ver Detalhes
  </a>

  <?php if ($userRole === 'seller' && $event['created_by'] === $userId): ?>
  <a 
    href="/events/save?id=<?=$event['id']?>" 
    class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition"
  >
    Editar Evento
  </a>

  <form 
    method="POST" 
    action="/events/delete/<?=$event['id']?>" 
    class="inline-block" 
    onSubmit="return confirm('Tem certeza que deseja excluir este evento? Esta ação não pode ser desfeita.');"
  >
    <button 
      type="submit" 
      class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition cursor-pointer"
    >
      Excluir Evento
    </button>
  </form>
  <?php endif; ?>
</div>

