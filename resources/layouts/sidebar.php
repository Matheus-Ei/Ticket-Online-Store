<?php use App\Utils\GeralUtils;?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title><?= $title ?? 'Home' ?></title>
  </head>

  <?php if ($isLoggedIn): ?>
  <body class="flex w-full">
    <aside class="w-64 bg-gray-950 text-white h-screen flex-shrink-0 overflow-y-auto">
      <div class="py-8 px-4">
        <?php
        if ($userRole === 'seller') {
        include GeralUtils::basePath('resources/partials/_sidebar_seller.php');
        } elseif ($userRole === 'client') {
        include GeralUtils::basePath('resources/partials/_sidebar_client.php');
        }
        ?>
      </div>
    </aside>
    <?php endif; ?>

    <div class="flex bg-gray-50 px-4 py-8 items-start justify-center w-full h-screen overflow-y-auto">
      <?= $content ?? '' ?>
    </div>
  </body>
</html>
