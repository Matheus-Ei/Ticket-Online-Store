<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg">
  <h1 class="text-3xl font-semibold text-gray-800 mb-1">Detalhes do Ingresso</h1>

  <p class="text-gray-600 mb-6">Aqui estão os detalhes do seu ingresso:</p>

  <div class="bg-white border border-gray-200 rounded-lg p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">ID do Ticket: <?= htmlspecialchars($ticket['id']) ?></h2>
    <p class="text-gray-600 mb-2"><strong>ID do Evento:</strong> <?= htmlspecialchars($ticket['event_id']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Comprado em:</strong> <?= htmlspecialchars($ticket['created_at']) ?></p>
    <p class="text-gray-600 mb-2"><strong>ID do Cliente:</strong> <?= htmlspecialchars($ticket['client_id']) ?></p>
  </div>

  <div class="mt-6">
    <a href="/tickets/purchased" class="text-blue-600 hover:underline">Voltar para Ingressos Comprados</a>
  </div>
</div>
