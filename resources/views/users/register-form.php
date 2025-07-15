<div class="flex items-center justify-center min-h-screen bg-gray-50"> 
  <form action="/users/register" method="POST" class="bg-white p-8 rounded-lg w-full max-w-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-center">Registrar</h2>

    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>

      <input 
        type="text" 
        id="name" 
        name="name" 
        placeholder="Digite seu nome"
        required 
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
      >
    </div>

    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>

      <input 
        type="email" 
        id="email" 
        placeholder="Digite seu email"
        name="email" 
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

    <div class="mb-4">
      <label for="role" class="block text-sm font-medium text-gray-700">Cargo</label>

      <select 
        id="role" 
        name="role" 
        placeholder="Selecione seu cargo"
        required 
        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
      >
        <option value="seller">Vendedor</option>

        <option value="client">Cliente</option>
      </select>
    </div>

    <button 
      type="submit" 
      class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"
    >
      Registrar
    </button>

    <p class="mt-4 text-center text-sm text-gray-600">
      Já tem uma conta? 

      <a href="/users/login" class="text-blue-600 hover:text-blue-500">Entrar</a>
    </p>
  </form>
</div>
