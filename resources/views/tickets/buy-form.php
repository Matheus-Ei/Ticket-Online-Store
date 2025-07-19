<div class="flex flex-col container mx-auto">
  <h1 class="text-2xl font-bold mb-1">Comprar um Ingresso</h1>

  <p class="text-gray-700 mb-4">Por favor, confirme que deseja realmente comprar este ingresso.</p>

  <div class="bg-white p-4 rounded shadow mb-4">
    <h1 class="text-2xl font-semibold">Detalhes do Evento</h1>  
    <p class="text-gray-600 mb-4">Você está comprando um ingresso para o evento:</p>

    <p class="text-md text-gray-500">Você tem 2 minutos para completar a compra antes que a reserva expire.</p>
    <p class="text-lg text-gray-500 mb-4 text-yellow-600">Tempo restante: <span id="countdown"></span></p>

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
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

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

<?php $reservationTimeISO = date('c', strtotime($reservationTime))?>

<?php if (isset($reservationTime)): ?>
<script>
const reservationTime = new Date("<?= htmlspecialchars($reservationTimeISO) ?>").getTime();
const countdownElement = document.getElementById('countdown');
const ticketId = <?= isset($ticketId) ? json_encode($ticketId) : 'null' ?>;

function expireReservation() {
  if (ticketId) {
    fetch(`/tickets/expire/${ticketId}`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'}
    })
  }
}

function updateCountdown() {
  const now = new Date().getTime();
  const distance = reservationTime + 120000 - now;

  if (distance < 0) {
    countdownElement.innerHTML = "Reserva expirada, por favor, tente novamente.";

    expireReservation();

    // Send back to buy page after 2 seconds
    setTimeout(() => {
      window.location.href = '/events/<?= htmlspecialchars($event['id']) ?>';
    }, 2000);

    clearInterval(countdownInterval);
  } else {
    const minutes = Math.floor(distance / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    countdownElement.innerHTML = `${minutes}m ${seconds}s`;
  }
}

const countdownInterval = setInterval(updateCountdown, 1000);
updateCountdown();
</script>
<?php endif; ?>
