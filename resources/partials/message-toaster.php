<?php if (isset($message) && !empty($message['text'])): ?>
<div id="error-toaster" class="fixed top-4 right-4 flex items-center w-fit max-w-xl p-4 gap-x-4 text-white bg-gray-800 rounded-md" role="alert">
  <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
    <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
    </svg>
    <span class="sr-only">Error icon</span>
  </div>

  <div>
    <h3 class="font-semibold"><?= htmlspecialchars(strtoupper($message['type']) ?? 'Error') ?></h3>
    <p class="text-sm font-normal flex-grow"><?= htmlspecialchars($message['text']) ?></p>
  </div>

  <button type="button" id="close-toaster" class="p-1.5 rounded-lg hover:opacity-80 focus:outline-none cursor-pointer ml-4">
    <span class="sr-only">Close</span>

    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
    </svg>
  </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const toaster = document.getElementById('error-toaster');
  const closeButton = document.getElementById('close-toaster');

  // Automatically hide the toaster after 5 seconds
  setTimeout(() => {
    toaster.style.display = 'none';
  }, 5000);

  // Close toaster when the close button is clicked
  closeButton.addEventListener('click', () => {
    toaster.style.display = 'none';
  });

  // Change the collor of the toaster based on the message type
  if (toaster) {
    const messageType = '<?= htmlspecialchars($message['type'] ?? 'error') ?>';

    if (messageType === 'success') {
      toaster.classList.replace('bg-gray-800', 'bg-green-800');
    } else if (messageType === 'warning') {
      toaster.classList.replace('bg-gray-800', 'bg-yellow-800');
    } else if (messageType === 'info') {
      toaster.classList.replace('bg-gray-800', 'bg-blue-800');
    } else {
      toaster.classList.replace('bg-gray-800', 'bg-red-800');
    }
  }

});
</script>
<?php endif; ?>
