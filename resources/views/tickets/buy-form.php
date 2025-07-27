<div class="flex flex-col container mx-auto">
  <h1 class="text-2xl font-bold mb-1">Comprar um Ingresso</h1>

  <p class="text-gray-700 mb-4">Por favor, confirme que deseja realmente comprar este ingresso.</p>

  <div class="bg-white p-4 rounded shadow mb-4">
    <h1 class="text-2xl font-semibold">Detalhes do Evento</h1>  
    <p class="text-gray-600 mb-4">Você está comprando um ingresso para o evento:</p>

    <?php if (isset($reservationTime)): ?>
    <p class="text-md text-gray-500">Você tem 2 minutos para completar a compra antes que a reserva expire.</p>
    <p class="text-lg text-gray-500 mb-4 text-yellow-600">Tempo restante: <span id="countdown"></span></p>
    <?php endif ?>

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

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-fit cursor-pointer">
      Confirmar Compra
    </button>
  </form>
</div>

<?php $reservationTimeUTC = (new DateTime($reservationTime))->format('Y-m-d\TH:i:s\Z');?>

<?php if (isset($reservationTime)): ?>
<script>
const reservationTimeUTCString = "<?= htmlspecialchars($reservationTimeUTC) ?>";
const reservationTime = new Date(reservationTimeUTCString).getTime();

const countdownElement = document.getElementById('countdown');

function expireReservation() {
  fetch(`/tickets/expire`, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      csrf_token: '<?= htmlspecialchars($csrf_token) ?>',
      event_id: <?= htmlspecialchars($event['id']) ?>
    })
  })
}

function updateCountdown() {
  const now = new Date().getTime();
  const distance = reservationTime + 120000 - now;

  if (distance < 0) {
    countdownElement.innerHTML = "Reserva expirada, por favor, tente novamente.";

    expireReservation();

    // Send back to view page after 2 seconds
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
