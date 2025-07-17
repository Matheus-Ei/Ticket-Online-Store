<DOCTYPE html>
  <html lang="pt-BR">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Comprovante de Compra</title>

      <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
      }

      .header {
        text-align: center;
        margin-bottom: 20px;
      }

      .header img {
        width: 150px;
      }

      .ticket-info {
        border: 1px solid #000;
        padding: 15px;
        margin-bottom: 20px;
      }

      .ticket-info h2 {
        margin-top: 0;
      }

      .footer {
        text-align: center;
        font-size: 12px;
        color: #666;
      }
      </style>
    </head>

    <body>
      <div class="header">
        <h1>Comprovante de Compra</h1>
        <p>Obrigado por comprar conosco! use este comprovante para acessar o evento.</p>
      </div>

      <div class="ticket-info">
        <h2>Detalhes do Ingresso</h2>
        <p>Aqui estão os detalhes do seu ingresso:</p>

        <p><strong>Nome do Evento:</strong> <?= $ticket['name'] ?></p>
        <p><strong>Data e Hora de Inicio:</strong> <?= date('d/m/Y H:i', strtotime($ticket['start_time'])) ?></p>
        <p><strong>Nome do Cliente:</strong> <?= $ticket['client_name'] ?></p>

        <?php if (!empty($ticket['location'])): ?>
        <p><strong>Local:</strong> <?= $ticket['location'] ?></p>
        <?php endif; ?>

        <p><strong>Email do Cliente:</strong> <?= $ticket['client_email'] ?></p>
        <p><strong>Status:</strong> <?= ucfirst($ticket['status']) ?></p>
        <p><strong>Data da Compra:</strong> <?= date('d/m/Y H:i:s', strtotime($ticket['created_at'])) ?></p>
      </div>

      <div class="footer">
        <p>Obrigado por comprar conosco!</p>
      </div>
    </body>
  </html>
</DOCTYPE>
