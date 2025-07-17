<?php

namespace App\Models;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use Config\Database;

class UserModel {
  public function getById($id) {
    return Database::selectOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
  }

  public function getAll() {
    return Database::selectAll("SELECT name, email, role FROM users");
  }

  public function getByEmail(string $email) {
    return Database::selectOne("SELECT * FROM users WHERE email = :email", ['email' => $email]);
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

    return Database::insert($query, $params);
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

    return Database::execute($query, $params);
  }

  public function delete($id) {
    $query = "DELETE FROM users WHERE id = :id";
    return Database::execute($query, ["id" => $id]);
  }
}
