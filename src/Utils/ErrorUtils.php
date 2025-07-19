<?php

namespace App\Utils;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use Core\DI\Container;
use Core\Session;
use PDOException;
use Throwable;

class ErrorUtils {
  private Session $session;

  public function __construct(
    Container $container
  ) {
    $this->session = $container->get(Session::class);
  }

  private function setMessage(string $type, string $text): void {
    $this->session->set('message', [
      'type' => $type,
      'text' => $text
    ]);
  }

  private function redirectPreviousPage(): void {
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    header("Location: $referer");
    exit();
  }

  private function renderError(string $errorMessage, int $statusCode, string $title = 'Erro'): void {
    $data = [
      'title' => $title,
      'errorMessage' => $errorMessage,
      'statusCode' => $statusCode,
      'isLoggedIn' => $this->session->get('user_id') && !empty($this->session->get('user_id')),
      'userRole' => $this->session->get('user_role'),
    ];

    extract($data);

    ob_start();
    include GeralUtils::basePath("resources/views/errors/error-card.php");
    $content = ob_get_clean();

    require GeralUtils::basePath("resources/layouts/sidebar.php");
    exit();
  }

  public function handleException(Throwable $e): void {
    switch (true) {
      case $e instanceof ValidationException:
        $this->setMessage('error', $e->getMessage());

        $this->session->set('old_input', $e->getInputData());

        $this->redirectPreviousPage();
        break;

      case $e instanceof PDOException:
        $this->renderError('Ocorreu um erro ao acessar o banco de dados. Por favor, tente novamente mais tarde.', 500, 'Erro de Banco de Dados');
        http_response_code(500);
        break;

      case $e instanceof NotFoundException:
        $this->renderError($e->getMessage(), 404, 'Não Encontrado');
        http_response_code(404);
        break;

      case $e instanceof UnauthorizedException:
        $this->setMessage('error', $e->getMessage());
        $this->redirectPreviousPage();
        break;

      default:
        // Render one error page for all other exceptions
        $this->renderError('Ocorreu um erro inesperado no servidor. Nossa equipe já foi notificada.', 500);
        http_response_code(500);
        break;
    }
  }
}
