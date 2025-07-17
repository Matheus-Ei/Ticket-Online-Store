<?php

namespace App\Controllers;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use App\Utils\SessionUtils;
use App\Services\UserService;
use App\Utils\MessageUtils;
use App\Validators\UserValidator;

class UserController extends AbstractController {
  public function __construct() {
    $this->service = new UserService();
    $this->validator = new UserValidator();
  }

  public function registerForm() {
    $data = [
      'title' => 'Criar Conta',
    ];

    $this->renderView('users/register-form', $data, 'clean');
  }

  public function loginForm() {
    $data = [
      'title' => 'Entrar',
    ];

    $this->renderView('users/login-form', $data, 'clean');
  }

  public function viewProfile() {
    $this->checkLogin();

    $userId = SessionUtils::getUserId();

    try {
      $user = $this->service->get($userId);
    } catch (\Exception $e) {
      return $this->renderError($e);
    }

    $data = [
      'title' => 'Perfil do Usuário',
      'user' => $user,
    ];

    $this->renderView('users/view-profile', $data);
  }

  public function editForm() {
    $this->checkLogin();

    $userId = SessionUtils::getUserId();

    try {
      $user = $this->service->get($userId);
    } catch (\Exception $e) {
      return $this->renderError($e);
    }

    $data = [
      'title' => 'Editar Perfil',
      'user' => $user,
    ];

    $this->renderView('users/edit-form', $data);
  }

  public function register() {
    try {
      $registrationData = new UserData(
        name: $_POST['name'],
        email: $_POST['email'],
        password: $_POST['password'],
        role: $_POST['role']
      );

      // Validate the registration data
      $this->validator->validateData($registrationData);

      $this->service->create($registrationData);

      MessageUtils::setMessage('success', 'Conta criada com sucesso! Faça login para continuar.');
      $this->navigate('/users/login');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/register');
    }
  }

  public function login() {
    try {
      $this->service->login($_POST['email'], $_POST['password']);

      MessageUtils::setMessage('success', 'Login realizado com sucesso!');
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/login');
    }
  }

  public function logout() {
    $this->checkLogin();

    session_destroy();
    $this->navigate('/');
  }

  public function edit() {
    $this->checkLogin();

    $userId = SessionUtils::getUserId();

    try {
      $profileData = new UserDataEdit(
        name: $_POST['name'],
        email: $_POST['email']
      );

      $this->validator->validateDataEdit($profileData);

      $this->service->update($userId, $profileData);

      MessageUtils::setMessage('success', 'Perfil atualizado com sucesso!');
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/edit');
    }
  }

  public function delete() {
    $this->checkLogin();

    $userId = SessionUtils::getUserId();

    try {
      $this->service->delete($userId);
      session_destroy();

      $this->navigate('/');
    } catch (\Exception $e) {
      MessageUtils::setMessage('error', $e->getMessage());
      $this->navigate('/users/profile');
    }
  }
}
