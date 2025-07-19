<?php

namespace App\Services;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use App\Models\UserModel;

class UserService extends AbstractService {
  public function __construct(
    private UserModel $model
  ) {}

  public function get($id) {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new NotFoundException("Usuário não encontrado");
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
      throw new UnauthorizedException("Credenciais inválidas");
    }

    // TODO: Send this verification to controller
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];

    return $user;
  }

  public function create(UserData $data) {
    // Check if email already exists
    $existingUser = $this->model->getByEmail($data->email);
    if ($existingUser) {
      throw new ValidationException(message: "Email já cadastrado");
    }

    // Hash the password
    $data->password = $this->hashPassword($data->password);
    return $this->model->create($data);
  }

  public function update($id, UserDataEdit $data) {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new NotFoundException("Usuário não encontrado");
    }

    return $this->model->update($id, $data);
  }

  public function delete($id) {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new NotFoundException("Usuário não encontrado");
    }

    return $this->model->delete($id);
  }
}
