<?php

namespace App\Controllers;

use App\DTOs\UserData;
use Config\Database;

class UserModel {
  public function get(int $id) {
    return Database::selectOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
  }

  public function getAll() {
    return Database::selectAll("SELECT * FROM users");
  }

  public function create(UserData $data) {
    $query = "INSERT INTO users (name, password_hash, email, role) 
    VALUES (:name, :password_hash, :email, :role)";

    $params = [
      "name" => $data->name,
      "password_hash" => $data->passwordHash,
      "email" => $data->email,
      "role" => $data->role
    ];

    return Database::insert($query, $params);
  }

  public function update(int $id, UserData $data) {
    $query = "UPDATE users SET 
    name = :name, 
    password_hash = :password_hash, 
    email = :email, 
    role = :role 
    WHERE id = :id";

    $params = [
      "id" => $id,
      "name" => $data->name,
      "password_hash" => $data->passwordHash,
      "email" => $data->email,
      "role" => $data->role
    ];

    return Database::execute($query, $params);
  }

  public function delete(int $id) {
    $query = "DELETE FROM users WHERE id = :id";
    return Database::execute($query, ["id" => $id]);
  }
}
