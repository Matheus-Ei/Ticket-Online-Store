<div class="container flex flex-col mx-auto">
  <h1 class="text-3xl font-bold mb-2">Meus Eventos</h1>

  <p class="text-gray-600 mb-6">
    Aqui estão os eventos que você criou. Você pode visualizar os detalhes de cada evento, incluindo os ingressos vendidos.
  </p>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($events as $event) {
    require 'partials/event.php';
    }?>
  </div>
</div>
