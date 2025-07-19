<?php

namespace App\Models;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use Core\Database;

class UserModel extends AbstractModel {
  public function __construct(protected Database $database) {
    parent::__construct($database);
  }

  public function getById($id): ?array {
    return $this->database->selectOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
  }

  public function getAll(): array {
    return $this->database->selectAll("SELECT name, email, role FROM users");
  }

  public function getByEmail(string $email): ?array {
    return $this->database->selectOne("SELECT * FROM users WHERE email = :email", ['email' => $email]);
  }

  public function create(UserData $data): int {
    $query = "INSERT INTO users (name, password_hash, email, role) 
              VALUES (:name, :password_hash, :email, :role)";

    $params = [
      "name" => $data->name,
      "password_hash" => $data->password,
      "email" => $data->email,
      "role" => $data->role
    ];

    return $this->database->insert($query, $params);
  }

  public function update($id, UserDataEdit $data): int {
    $query = "UPDATE users SET 
                name = :name, 
                email = :email
              WHERE id = :id";

    $params = [
      "id" => $id,
      "name" => $data->name,
      "email" => $data->email,
    ];

    return $this->database->execute($query, $params);
  }

  public function delete($id): int {
    $query = "DELETE FROM users WHERE id = :id";
    return $this->database->execute($query, ["id" => $id]);
  }
}
