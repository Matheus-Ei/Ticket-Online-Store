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

  public function get(int $id): array {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new NotFoundException("Usuário não encontrado");
    }

    return $user;
  }

  public function getAll(): array {
    return $this->model->getAll();
  }

  public function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public function login(string $email, string $password): array {
    $user = $this->model->getByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
      throw new UnauthorizedException("Credenciais inválidas");
    }

    return $user;
  }

  public function create(UserData $data): int {
    // Check if email already exists
    $existingUser = $this->model->getByEmail($data->email);
    if ($existingUser) {
      throw new ValidationException(message: "Email já cadastrado");
    }

    // Hash the password
    $data->password = $this->hashPassword($data->password);
    return $this->model->create($data);
  }

  public function update($id, UserDataEdit $data): int {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new NotFoundException("Usuário não encontrado");
    }

    return $this->model->update($id, $data);
  }

  public function delete($id): int {
    $user = $this->model->getById($id);

    if (!$user) {
      throw new NotFoundException("Usuário não encontrado");
    }

    return $this->model->delete($id);
  }
}
