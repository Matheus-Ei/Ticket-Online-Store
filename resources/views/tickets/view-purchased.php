<div class="flex flex-col container mx-auto p-6 bg-white border border-gray-200 rounded-lg">
  <h1 class="text-3xl font-semibold text-gray-800 mb-1">Ingresos Comprados</h1>
  <p class="text-gray-600 mb-6">Aqui você pode ver todos os ingressos que comprou.</p>

  <?php if (empty($purchasedTickets)): ?>
  <p class="text-gray-600">Você ainda não comprou nenhum ingresso.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Nome do Evento</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Data e Hora de Inicio</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Localização</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Status</th>
          <th class="py-3 px-4 text-left text-sm font-medium text-gray-700 border-b">Ações</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($purchasedTickets as $ticket) {
        require 'partials/ticket-row.php';
        }?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
