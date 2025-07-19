<div class="container mx-auto">
  <form action="/events/save" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <h1 class="text-3xl font-bold mb-2"><?= isset($event) ? 'Editar Evento' : 'Criar Evento' ?></h1>

    <p class="text-gray-600 mb-6">
      <?= isset($event) ? 'Edite os detalhes do evento abaixo.' : 'Preencha os detalhes do novo evento.' ?>
    </p>

    <input type="hidden" name="id" value="<?= isset($event) ? htmlspecialchars($event['id']) : '' ?>">

    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">* Nome do Evento</label>

      <input 
        type="text" 
        id="name" 
        placeholder="Que nome você gostaria de dar ao evento?"
        name="name" 
        value="<?= isset($event) ? htmlspecialchars($event['name']) : '' ?>" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>

      <textarea 
        id="description" 
        name="description" 
        rows="4" 
        placeholder="Descreva o evento"
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      ><?= isset($event) ? htmlspecialchars($event['description']) : '' ?></textarea>
    </div>

    <div>
      <label for="image_url" class="block text-sm font-medium text-gray-700">URL da Imagem</label>

      <input 
        type="url" 
        id="image_url" 
        name="image_url" 
        placeholder="Insira a URL da imagem do evento"
        value="<?= isset($event) ? htmlspecialchars($event['image_url']) : '' ?>" 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="start_time" class="block text-sm font-medium text-gray-700">* Data e Hora de Início</label>

      <input 
        type="datetime-local" 
        id="start_time" 
        name="start_time" 
        placeholder="Selecione a data e hora de início"
        value="<?= isset($event) ? date('Y-m-d\TH:i', strtotime($event['start_time'])) : '' ?>" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="end_time" class="block text-sm font-medium text-gray-700">Data e Hora de Término</label>

      <input 
        type="datetime-local" 
        id="end_time" 
        name="end_time" 
        placeholder="Selecione a data e hora de término"
        value="<?= isset($event) && $event['end_time'] ? date('Y-m-d\TH:i', strtotime($event['end_time'])) : null ?>"
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="location" class="block text-sm font-medium text-gray-700">Localização</label>

      <input 
        type="text" 
        id="location" 
        name="location" 
        placeholder="Qual é o local do evento?"
        value="<?= isset($event) ? htmlspecialchars($event['location']) : '' ?>" 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="ticket_price" class="block text-sm font-medium text-gray-700">* Preço do Ingresso</label>

      <input 
        type="number" 
        id="ticket_price" 
        name="ticket_price" 
        placeholder="Insira o preço do ingresso"
        value="<?= isset($event) ? htmlspecialchars($event['ticket_price']) : '0.00' ?>" 
        step="0.01" 
        min="0" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="ticket_quantity" class="block text-sm font-medium text-gray-700">* Quantidade de Ingressos</label>

      <input 
        type="number" 
        id="ticket_quantity" 
        name="ticket_quantity" 
        placeholder="Quantos ingressos estão disponíveis?"
        value="<?= isset($event) ? htmlspecialchars($event['ticket_quantity']) : '0' ?>" 
        min="0" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <button 
      type="submit" 
      class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition cursor-pointer"
    >
      <?= isset($event) ? 'Atualizar Evento' : 'Criar Evento' ?>
    </button>
  </form>
</div>
