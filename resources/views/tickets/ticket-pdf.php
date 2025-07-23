<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Compra</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    body {
      font-family: 'Poppins', Arial, sans-serif;
      margin: 0;
      padding: 20px;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .container {
      max-width: 680px;
      margin: 20px auto;
      background-color: #ffffff;
      border: 1px solid #e9ecef;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }

    .header {
      background-color: #0052cc;
      color: #ffffff;
      padding: 30px 20px;
      text-align: center;
    }

    .header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: 700;
    }

    .header p {
      margin: 8px 0 0;
      font-size: 16px;
      opacity: 0.9;
    }

    .content {
      padding: 35px 40px;
    }

    .ticket-info h2 {
      margin-top: 0;
      margin-bottom: 25px;
      font-size: 22px;
      color: #333;
      border-bottom: 2px solid #0052cc;
      padding-bottom: 10px;
      font-weight: 600;
    }

    .ticket-info p {
      font-size: 16px;
      line-height: 1.7;
      color: #555;
      margin: 15px 0;
      border-bottom: 1px solid #e9ecef;
      padding-bottom: 15px;
    }

    .ticket-info p:last-of-type {
      border-bottom: none;
    }

    .ticket-info p strong {
      color: #333;
      font-weight: 600;
      min-width: 180px;
      display: inline-block;
    }

    .footer {
      text-align: center;
      padding: 25px;
      font-size: 14px;
      color: #888;
      background-color: #f4f7f6;
    }

    .qr-code {
      display: block;
      margin: 20px auto;
      width: 150px;
      height: auto;
    }
    </style>
  </head>

  <body>
    <div class="container">
      <div class="header">
        <h1>Comprovante de Compra</h1>

        <p>Use este comprovante para acessar o evento.</p>
      </div>

      <div class="content">
        <div class="ticket-info">
          <h2>Detalhes do Ingresso</h2>

          <p><strong>Nome do Evento:</strong> <?= $ticket['name'] ?></p>
          <p><strong>Data e Hora de Início:</strong> <?= date('d/m/Y H:i', strtotime($ticket['start_time'])) ?></p>
          <p><strong>Nome do Cliente:</strong> <?= $ticket['client_name'] ?></p>

          <?php if (!empty($ticket['location'])): ?>
          <p><strong>Local:</strong> <?= $ticket['location'] ?></p>
          <?php endif; ?>

          <p><strong>Email do Cliente:</strong> <?= $ticket['client_email'] ?></p>
          <p><strong>Status:</strong> <?= ucfirst($ticket['status']) ?></p>
          <p><strong>Data da Compra:</strong> <?= date('d/m/Y H:i:s', strtotime($ticket['created_at'])) ?></p>

          <img src="<?= $ticket['qr_code'] ?>" alt="QR Code" class="qr-code">
        </div>
      </div>

      <div class="footer">
        <p>Obrigado por comprar conosco!</p>
      </div>
    </div>
  </body>
</html>
