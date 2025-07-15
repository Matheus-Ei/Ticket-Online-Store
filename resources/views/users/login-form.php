<div class="min-h-screen flex items-center justify-center bg-gray-50">
  <form method="POST" action="/users/login" class="bg-white p-6 rounded-lg border border-gray-200 w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-center">Entrar</h2>

    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>

      <input 
        type="email" 
        id="email" 
        name="email" 
        placeholder="Digite seu email"
        required 
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
      >
    </div>

    <div class="mb-4">
      <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>

      <input
        type="password" 
        id="password" 
        name="password" 
        placeholder="Digite sua senha"
        required 
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
      >
    </div>

    <button 
      type="submit" 
      class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"
    >
      Entrar
    </button>

    <p class="mt-4 text-center text-sm text-gray-600"> 
      Não tem uma conta? 

      <a href="/users/register" class="text-blue-600 hover:text-blue-500">Registrar</a>
    </p>
  </form>
</div>
