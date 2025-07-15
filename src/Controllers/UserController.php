<?php

namespace App\Controllers;

use App\DTOs\UserData;
use App\Utils\SessionUtils;
use App\Models\UserModel;
use App\Utils\MessageUtils;
use App\Validators\UserValidator;

class UserController extends AbstractController {
  public function __construct() {
    $this->model = new UserModel();
    $this->validator = new UserValidator();
  }

  public function registerForm() {
    $data = [
      'title' => 'Criar Conta',
    ];

    $this->render('resources/views/users/register-form.php', $data, 'clean');
  }

  public function loginForm() {
    $data = [
      'title' => 'Entrar',
    ];

    $this->render('resources/views/users/login-form.php', $data, 'clean');
  }

  public function viewProfile() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'Perfil do Usuário',
      'user' => $user,
    ];

    $this->render('resources/views/users/view-profile.php', $data);
  }

  public function editForm() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'Editar Perfil',
      'user' => $user,
    ];

    $this->render('resources/views/users/edit-form.php', $data);
  }

  public function register() {
    try {
      $hashed_password = $this->model->hashPassword($_POST['password']);

      $registrationData = new UserData(
        name: $_POST['name'],
        email: $_POST['email'],
        passwordHash: $hashed_password,
        role: $_POST['role']
      );

      // Validate the registration data
      $this->validator->validateData($registrationData);

      $this->model->create($registrationData);

      MessageUtils::setMessage('success', 'Conta criada com sucesso! Faça login para continuar.');
      $this->navigate('/users/login');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/register');
    }
  }

  public function login() {
    try {
      $this->model->login($_POST['email'], $_POST['password']);

      MessageUtils::setMessage('success', 'Login realizado com sucesso!');
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/login');
    }
  }

  public function logout() {
    $this->ensureLoggedIn();

    session_destroy();
    $this->navigate('/');
  }

  public function edit() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();

    try {
      $user = $this->model->get($userId);

      $profileData = new UserData(
        name: $_POST['name'],
        email: $_POST['email'],
        passwordHash: $user['password_hash'],
        role: $user['role'],
      );

      // Validate the profile data
      $this->validator->validateData($profileData);

      $this->model->update($userId, $profileData);

      MessageUtils::setMessage('success', 'Perfil atualizado com sucesso!');
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/edit');
    }
  }

  public function delete() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();

    try {
      $this->model->delete($userId);
      session_destroy();

      $this->navigate('/');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/profile');
    }
  }
}
