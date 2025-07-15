<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title><?= $title ?? 'Home' ?></title>
  </head>

  <body class="flex w-full">
    <?php if (isset($sidebar)) : ?>
    <aside class="w-64 bg-gray-950 text-white h-screen">
      <div class="p-4">
        <h2 class="text-xl font-bold mb-4"><?= $sidebar['title'] ?? 'Menu' ?></h2>

        <ul class="space-y-2">
          <?php foreach ($sidebar['links'] as $link) : ?>
          <li><a href="<?= $link['url'] ?>" class="text-gray-300 hover:text-white"><?= $link['label'] ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
    <?php endif; ?>

    <div class="w-full h-full">
      <?= $content ?? '' ?>
    </div>
  </body>
</html>
