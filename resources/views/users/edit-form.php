<div class="flex items-center justify-center min-h-screen bg-gray-50">
  <div class="bg-white border border-gray-200 rounded-lg p-6 w-3/4">
    <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>

    <form action="/users/edit" method="post">
      <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>

        <input 
          type="text" 
          id="name" 
          name="name" 
          value="<?=htmlspecialchars($user['name'])?>" 
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          required
        >
      </div>

      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>

        <input 
          type="email" 
          id="email" 
          name="email" 
          value="<?=htmlspecialchars($user['email'])?>" 
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          required
        >
      </div>

      <div class="flex space-x-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 cursor-pointer">Update Profile</button>
      </div>
    </form>
  </div>
</div>

