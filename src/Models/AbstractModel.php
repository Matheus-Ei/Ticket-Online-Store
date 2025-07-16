<?php

namespace App\Models;

abstract class AbstractModel {
  abstract public function get(int $id): ?array;

  abstract public function getAll(): array;

  abstract public function create(object $data): int;

  abstract public function update(int $id, object $data): bool;

  abstract public function delete(int $id): bool;
}
