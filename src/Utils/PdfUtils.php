<?php

namespace App\Utils;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfUtils {
  public static function render(string $htmlPath, string $filename, array $variables): void {
    if (!file_exists($htmlPath)) {
      throw new \Exception("Arquivo HTML não encontrado: $htmlPath");
    }

    extract($variables);

    ob_start();
    include $htmlPath;
    $html = ob_get_clean();

    if (empty($html)) {
      throw new \Exception("O conteúdo HTML está vazio ou não foi carregado corretamente.");
    }

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Envia o PDF para o navegador para download
    $dompdf->stream($filename, ["Attachment" => true]);
  }
}
