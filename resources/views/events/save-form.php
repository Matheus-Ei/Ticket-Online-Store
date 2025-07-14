<div class="container mx-auto px-4 py-6">
  <form action="/events/save" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
    <h1 class="text-3xl font-bold mb-2"><?= isset($event) ? 'Edit Event' : 'Create Event' ?></h1>

    <p class="text-gray-600 mb-6">
      Please fill out the form below to <?= isset($event) ? 'update' : 'create' ?> an event. All fields are required.
    </p>

    <input type="hidden" name="id" value="<?= isset($event) ? htmlspecialchars($event['id']) : '' ?>">

    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Event Name</label>

      <input 
        type="text" 
        id="name" 
        placeholder="Enter event name"
        name="name" 
        value="<?= isset($event) ? htmlspecialchars($event['name']) : '' ?>" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="description" class="block text-sm font-medium text-gray-700">Description</label>

      <textarea 
        id="description" 
        name="description" 
        rows="4" 
        placeholder="Enter event description"
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      ><?= isset($event) ? htmlspecialchars($event['description']) : '' ?></textarea>
    </div>

    <div>
      <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>

      <input 
        type="url" 
        id="image_url" 
        name="image_url" 
        placeholder="Enter image URL"
        value="<?= isset($event) ? htmlspecialchars($event['image_url']) : '' ?>" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>

      <input 
        type="datetime-local" 
        id="start_time" 
        name="start_time" 
        placeholder="Select start time"
        value="<?= isset($event) ? date('Y-m-d\TH:i', strtotime($event['start_time'])) : '' ?>" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>

      <input 
        type="datetime-local" 
        id="end_time" 
        name="end_time" 
        placeholder="Select end time"
        value="<?= isset($event) && $event['end_time'] ? date('Y-m-d\TH:i', strtotime($event['end_time'])) : '' ?>"
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="location" class="block text-sm font-medium text-gray-700">Location</label>

      <input 
        type="text" 
        id="location" 
        name="location" 
        placeholder="Enter event location"
        value="<?= isset($event) ? htmlspecialchars($event['location']) : '' ?>" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="ticket_price" class="block text-sm font-medium text-gray-700">Ticket Price</label>

      <input 
        type="number" 
        id="ticket_price" 
        name="ticket_price" 
        placeholder="Enter ticket price"
        value="<?= isset($event) ? htmlspecialchars($event['ticket_price']) : '0.00' ?>" 
        step="0.01" 
        min="0" 
        required 
        class="px-2 py-1 mt-1 block w-full border-gray-200 rounded-md border focus:border-blue-500 focus:ring-blue-500 outline-none"
      >
    </div>

    <div>
      <label for="ticket_quantity" class="block text-sm font-medium text-gray-700">Ticket Quantity</label>

      <input 
        type="number" 
        id="ticket_quantity" 
        name="ticket_quantity" 
        placeholder="Enter number of tickets available"
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
      <?= isset($event) ? 'Update Event' : 'Create Event' ?>
    </button>
  </form>
</div>
