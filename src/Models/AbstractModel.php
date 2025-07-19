<?php

namespace App\Models;

use App\DTOs\DataInterface;
use Core\Database;

abstract class AbstractModel {
  public function __construct(
    protected Database $database,
  ) {}

  public function createTransaction(): void {
    $this->database->beginTransaction();
  }

  public function commitTransaction(): void {
    $this->database->commit();
  }

  public function rollbackTransaction(): void {
    $this->database->rollback();
  }

  abstract public function getById(int $id): ?array;

  abstract public function getAll(): array;

  abstract public function delete(int $id): int;

  // abstract public function create(DataInterface $data): int;

  // abstract public function update(int $id, DataInterface $data): int;

}
