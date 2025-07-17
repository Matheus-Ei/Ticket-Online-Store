<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg">
  <h1 class="text-3xl font-semibold text-gray-800 mb-1">Detalhes do Ingresso</h1>

  <p class="text-gray-600 mb-6">Aqui estão os detalhes do seu ingresso:</p>

  <div class="bg-white border border-gray-200 rounded-lg p-6">
    <p class="text-gray-600 mb-2"><strong>Nome do Evento:</strong> <?= htmlspecialchars($ticket['name']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Data e Hora de Início:</strong> <?= htmlspecialchars($ticket['start_time']) ?></p>

    <?php if (isset($ticket['end_time'])):?>
    <p class="text-gray-600 mb-2"><strong>Data e Hora de Fim:</strong> <?= htmlspecialchars($ticket['end_time']) ?></p>
    <?php endif; ?>

    <p class="text-gray-600 mb-2"><strong>Localização:</strong> <?= htmlspecialchars($ticket['location']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p>
    <p class="text-gray-600 mb-2"><strong>Comprado em:</strong> <?= htmlspecialchars($ticket['created_at']) ?></p>

    <div class="mt-4">
      <a href="/tickets/generate-pdf/<?= $ticket['id'] ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Baixar Comprovante
      </a>
    </div>
  </div>

  <div class="mt-6">
    <a href="/tickets/purchased" class="text-blue-600 hover:underline">Voltar para Ingressos Comprados</a>
  </div>
</div>
