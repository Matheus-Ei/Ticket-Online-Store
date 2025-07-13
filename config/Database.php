<?php

namespace Config;

use PDO;
use PDOException;
use App\Utils\GeralUtils;

class Database {
  private static $pdo = null;

  public static function connect() {
    if (self::$pdo === null) {
      $host = GeralUtils::getEnv('DATABASE_HOST');
      $port = GeralUtils::getEnv('DATABASE_PORT');
      $dbname = GeralUtils::getEnv('DATABASE_NAME');
      $user = GeralUtils::getEnv('DATABASE_USER');
      $password = GeralUtils::getEnv('DATABASE_PASSWORD');

      try {
        self::$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
      } catch (PDOException $e) {
        die("Error connecting the database: " . $e->getMessage());
      }
    }

    return self::$pdo;
  }

  public static function disconnect() {
    self::$pdo = null;
  }

  public static function selectAll($query, $params = []) {
    $stmt = self::$pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  public static function selectOne($query, $params = []) {
    $stmt = self::$pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
  }

  public static function insert($query, $params = []) {
    $stmt = self::$pdo->prepare($query);
    $stmt->execute($params);
    return self::$pdo->lastInsertId();
  }

  public static function query($query, $params = []) {
    $stmt = self::$pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }
}
