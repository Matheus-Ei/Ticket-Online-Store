<?php

namespace App\Services;

use App\DTOs\UserData;
use App\Models\UserModel;

class UserService extends AbstractService {
  public function __construct() {
    $this->model = new UserModel();
  }

  public function get($id) {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new \Exception("User not found");
    }

    return $user;
  }

  public function hashPassword(string $password) {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public function getAll() {
    return $this->model->getAll();
  }

  public function login(string $email, string $password) {
    $user = $this->model->getByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
      throw new \Exception("Invalid email or password");
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];

    return $user;
  }

  public function create(UserData $data) {
    // Hash the password
    $data->password = $this->hashPassword($data->password);
    return $this->model->create($data);
  }

  public function update($id, UserData $data) {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new \Exception("User not found");
    }

    // Update user data
    $data->role = $user['role'];
    $data->password = $user['password_hash'];

    return $this->model->update($id, $data);
  }

  public function delete($id) {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new \Exception("User not found");
    }

    return $this->model->delete($id);
  }
}
