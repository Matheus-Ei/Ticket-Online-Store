<?php

namespace App\Utils;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use PDOException;
use Throwable;

class ErrorUtils {
  public static function setMessage(string $type, string $text): void {
    $_SESSION['message'] = [
      'type' => $type,
      'text' => $text
    ];
  }

  public static function redirectPreviousPage(): void {
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    header("Location: $referer");
    exit();
  }

  private static function renderError(string $errorMessage, int $statusCode, string $title = 'Erro'): void {
    $data = [
      'title' => $title,
      'errorMessage' => $errorMessage,
      'statusCode' => $statusCode,
      'isLoggedIn' => isset($_SESSION['user_id']) && !empty($_SESSION['user_id']),
      'userRole' => $_SESSION['user_role'] ?? null,
    ];

    extract($data);

    ob_start();
    include GeralUtils::basePath("resources/views/errors/error-card.php");
    $content = ob_get_clean();

    require GeralUtils::basePath("resources/layouts/sidebar.php");
    exit();
  }

  public static function handleException(Throwable $e): void {
    switch (true) {
      case $e instanceof ValidationException:
        self::setMessage('error', $e->getMessage());

        $_SESSION['old_input'] = $e->getInputData();

        self::redirectPreviousPage();
        break;

      case $e instanceof PDOException:
        self::renderError('Ocorreu um erro ao acessar o banco de dados. Por favor, tente novamente mais tarde.', 500, 'Erro de Banco de Dados');
        http_response_code(500);
        break;

      case $e instanceof NotFoundException:
        self::renderError($e->getMessage(), 404, 'Não Encontrado');
        http_response_code(404);
        break;

      case $e instanceof UnauthorizedException:
        self::setMessage('error', $e->getMessage());
        self::redirectPreviousPage();
        break;

      default:
        // Render one error page for all other exceptions
        self::renderError('Ocorreu um erro inesperado no servidor. Nossa equipe já foi notificada.', 500);
        http_response_code(500);
        break;
    }
  }
}
