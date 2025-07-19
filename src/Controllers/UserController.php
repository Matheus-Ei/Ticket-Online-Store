<?php

namespace App\Controllers;

use App\DTOs\UserData;
use App\DTOs\UserDataEdit;
use App\Services\UserService;
use App\Validators\UserValidator;
use Core\Request;
use Core\Session;

class UserController extends AbstractController {
  public function __construct(
    private Session $session,
    private Request $request,
    private UserService $service,
    private UserValidator $validator
  ) {
    parent::__construct($session, $request);
  }

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
    $registrationData = new UserData(
      name: $this->request->post('name'),
      email: $this->request->post('email'),
      password: $this->request->post('password'),
      role: $this->request->post('role')
    );

    // Validate the registration data
    $this->validator->validateData($registrationData);

    $this->service->create($registrationData);

    $this->setMessage('success', 'Conta criada com sucesso! Faça login para continuar.');
    $this->navigate('/users/login');
  }

  public function login() {
    $this->service->login(
      $this->request->post('email'), 
      $this->request->post('password')
    );

    $this->setMessage('success', 'Login realizado com sucesso!');
    $this->navigate('/users/profile');
  }

  public function logout() {
    $this->checkLogin();

    session_destroy();
    $this->navigate('/');
  }

  public function edit() {
    $this->checkLogin();

    $profileData = new UserDataEdit(
      name: $this->request->post('name'),
      email: $this->request->post('email')
    );

    $this->validator->validateDataEdit($profileData);

    $this->service->update($this->getUserId(), $profileData);

    $this->setMessage('success', 'Perfil atualizado com sucesso!');
    $this->navigate('/users/profile');
  }

  public function delete() {
    $this->checkLogin();

    $this->service->delete($this->getUserId());
    session_destroy();

    $this->navigate('/');
  }
}
