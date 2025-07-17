<div class="bg-white p-8 rounded-lg border border-gray-200 max-w-lg w-full">
  <h1 class="text-3xl font-bold text-red-600 mb-2"><?= $title ?? 'Erro' ?></h1>
  <p class="text-gray-500 mb-4">Status Code: <?= $statusCode ?? 500 ?></p>
  <p class="text-gray-700 mb-6"><?= $errorMessage ?? 'An unexpected error occurred. Please try again later.' ?></p>
  <a href="/" class="text-blue-500 hover:underline">Go back to home</a>
</div>
