<?php

namespace App\Models;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use Core\Database;

class UserModel {
  public function __construct(private Database $database) {}

  public function getById($id) {
    return $this->database->selectOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
  }

  public function getAll() {
    return $this->database->selectAll("SELECT name, email, role FROM users");
  }

  public function getByEmail(string $email) {
    return $this->database->selectOne("SELECT * FROM users WHERE email = :email", ['email' => $email]);
  }

  public function create(UserData $data) {
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

  public function update($id, UserDataEdit $data) {
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

  public function delete($id) {
    $query = "DELETE FROM users WHERE id = :id";
    return $this->database->execute($query, ["id" => $id]);
  }
}
