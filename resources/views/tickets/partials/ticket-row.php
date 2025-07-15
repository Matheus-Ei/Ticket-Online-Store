<tr class="hover:bg-gray-50">
  <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['id']) ?></td>

  <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['event_id']) ?></td>

  <td class="py-2 px-4 border-b border-gray-200"><?= htmlspecialchars($ticket['status']) ?></td>

  <td class="py-2 px-4 border-b border-gray-200">
    <a href="/tickets/<?= htmlspecialchars($ticket['id']) ?>" class="text-blue-600 hover:underline">Ver Detalhes</a>
  </td>
</tr>

