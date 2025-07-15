<?php if (isset($error) && !empty($error)): ?>
<div id="error-toaster" class="fixed top-4 right-4 flex items-center w-fit max-w-xl p-4 gap-3 text-white bg-gray-800 rounded-xl" role="alert">
  <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
    </svg>
    <span class="sr-only">Error icon</span>
  </div>

  <p class="text-sm font-normal flex-grow"><?= htmlspecialchars($error) ?></p>

  <button type="button" id="close-toaster" class="p-1.5 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 cursor-pointer">
    <span class="sr-only">Close</span>
    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
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
});
</script>
<?php endif; ?>
