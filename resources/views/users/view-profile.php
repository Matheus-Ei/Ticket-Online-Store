<div class="container bg-white border border-gray-200 rounded-lg p-6">
  <h1 class="text-2xl font-bold mb-4">Perfil do Usuário</h1>

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
    <p class="text-gray-900"><?=htmlspecialchars($user['name'])?></p>
  </div>

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
    <p class="text-gray-900"><?=htmlspecialchars($user['email'])?></p>
  </div>

  <div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Cargo:</label>
    <p class="text-gray-900"><?=htmlspecialchars($user['role'])?></p>
  </div>

  <div class="flex space-x-4">
    <a href="/users/edit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 cursor-pointer">Editar Perfil</a>

    <form action="/users/delete" method="post" onsubmit="return confirm('Are you sure you want to delete your account?');">
      <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 cursor-pointer">Deletar Conta</button>
    </form>

    <form action="/users/logout" method="post">
      <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">Sair</button>
    </form>
  </div>
</div> 
