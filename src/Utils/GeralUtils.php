<?php

namespace App\Utils;
use chillerlan\QRCode\QRCode;

class GeralUtils {
  static function basePath(string $path): string {
    $absolutePath = __DIR__ . '/../../' . $path;
    return $absolutePath;
  }

  public static function getEnv(string $name): string {
    return $_ENV[$name];
  }

  public static function inspect(mixed $value): void {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
  }

  public static function generateQRCode(string $url): string {
    $qrCodeOutput = (new QRCode)->render($url);

    return $qrCodeOutput;
  }
}
