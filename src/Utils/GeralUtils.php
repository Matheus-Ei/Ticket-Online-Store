<?php

namespace App\Utils;

class GeralUtils {
  static function basePath($path) {
    $absolutePath = __DIR__ . '/../../' . $path;
    return $absolutePath;
  }

  public static function getEnv($name) {
    return $_ENV[$name];
  }

  public static function inspect($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
  }
}
