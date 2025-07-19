<?php

namespace Core;

use PDO;
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

      $this->pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    }

    return $this->pdo;
  }

  public function disconnect() {
    $this->pdo = null;
  }

  public function selectAll(string $query, ?array $params = []): array {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $values = $stmt->fetchAll();
    return $values ? $values : [];
  }

  public function selectOne(string $query, ?array $params = []): ?array {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $value = $stmt->fetch();
    return $value ? $value : null;
  }

  public function insert(string $query, ?array $params = []): int {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    return $this->pdo->lastInsertId();
  }

  public function execute(string $query, ?array $params = []): int {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount();
  }

  public function beginTransaction() {
    $this->pdo->beginTransaction();
  }

  public function commit() {
    $this->pdo->commit();
  }

  public function rollback() {
    $this->pdo->rollBack();
  }
}
