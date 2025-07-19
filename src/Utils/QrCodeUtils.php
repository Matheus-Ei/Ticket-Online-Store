<?php

namespace App\Utils;

use chillerlan\QRCode\QRCode;

class QrCodeUtils {
  public static function generate(string $data): string {
    return (new QRCode)->render($data);
  }
}
