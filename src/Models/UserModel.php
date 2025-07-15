<?php

namespace App\Models;

use App\DTOs\UserData;
use Config\Database;

class UserModel {
  public function get(int $id) {
    $user = Database::selectOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
    return $user;
  }

  public function getAll() {
    return Database::selectAll("SELECT name, email, role FROM users");
  }

  public function getByEmail(string $email) {
    return Database::selectOne("SELECT * FROM users WHERE email = :email", ['email' => $email]);
  }

  public function hashPassword(string $password) {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public function login(string $email, string $password) {
    $user = $this->getByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
      throw new \Exception("Invalid email or password");
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];

    return $user;
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
