<div class="container flex flex-col mx-auto">
  <h1 class="text-3xl font-bold mb-2">Eventos Comprados</h1>

  <p class="text-gray-600 mb-6">
    Aqui estão os eventos que você comprou. Você pode visualizar os detalhes de cada evento. Se você é um vendedor, também pode criar novos eventos.
  </p>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($purchasedEvents as $event) {
    require 'partials/event.php';
    }?>
  </div>
</div>
