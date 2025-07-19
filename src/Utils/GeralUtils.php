<?php

namespace App\Utils;

class GeralUtils {
  public static function basePath(string $path): string {
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
}
