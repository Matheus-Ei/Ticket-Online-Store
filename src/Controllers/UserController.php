<?php

namespace App\Controllers;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use App\Services\UserService;
use App\Validators\UserValidator;

class UserController extends AbstractController {
  public function __construct(
    private UserService $service,
    private UserValidator $validator
  ) {}

  public function registerForm() {
    $data = ['title' => 'Criar Conta'];

    $this->renderView('users/register-form', $data, 'clean');
  }

  public function loginForm() {
    $data = ['title' => 'Entrar'];

    $this->renderView('users/login-form', $data, 'clean');
  }

  public function viewProfile() {
    $this->checkLogin();

    $user = $this->service->get($this->getUserId());

    $data = [
      'title' => 'Perfil do Usuário',
      'user' => $user,
    ];

    $this->renderView('users/view-profile', $data);
  }

  public function editForm() {
    $this->checkLogin();

    $user = $this->service->get($this->getUserId());

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

      $this->setMessage('success', 'Conta criada com sucesso! Faça login para continuar.');
      $this->navigate('/users/login');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/users/register');
    }
  }

  public function login() {
    try {
      $this->service->login($_POST['email'], $_POST['password']);

      $this->setMessage('success', 'Login realizado com sucesso!');
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
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

    try {
      $profileData = new UserDataEdit(
        name: $_POST['name'],
        email: $_POST['email']
      );

      $this->validator->validateDataEdit($profileData);

      $this->service->update($this->getUserId(), $profileData);

      $this->setMessage('success', 'Perfil atualizado com sucesso!');
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/users/edit');
    }
  }

  public function delete() {
    $this->checkLogin();

    try {
      $this->service->delete($this->getUserId());
      session_destroy();

      $this->navigate('/');
    } catch (\Exception $e) {
      $this->setMessage('error', $e->getMessage());
      $this->navigate('/users/profile');
    }
  }
}
