<?php

namespace Core;

use PDO;
use PDOException;
use App\Utils\GeralUtils;

class Database {
  private $pdo = null;

  public function __construct() {
    if ($this->pdo === null) {
      $host = GeralUtils::getEnv('DATABASE_HOST');
      $port = GeralUtils::getEnv('DATABASE_PORT');
      $dbname = GeralUtils::getEnv('DATABASE_NAME');
      $user = GeralUtils::getEnv('DATABASE_USER');
      $password = GeralUtils::getEnv('DATABASE_PASSWORD');

      try {
        $this->pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
      } catch (PDOException $e) {
        die("Error connecting the database: " . $e->getMessage());
      }
    }

    return $this->pdo;
  }

  public function disconnect() {
    $this->pdo = null;
  }

  public function selectAll($query, $params = []) {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  public function selectOne($query, $params = []) {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
  }

  public function insert($query, $params = []) {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    return $this->pdo->lastInsertId();
  }

  public function execute($query, $params = []) {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
  }
}
