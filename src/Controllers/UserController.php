<?php

namespace App\Controllers;

use App\DTOs\UserData;
use App\Utils\SessionUtils;
use App\Models\UserModel;

class UserController extends AbstractController {
  public function __construct() {
    $this->model = new UserModel();
  }

  public function registerForm() {
    $data = [
      'title' => 'Register',
    ];

    $this->render('resources/views/users/register-form.php', $data, 'clean');
  }

  public function loginForm() {
    $data = [
      'title' => 'Login',
    ];

    $this->render('resources/views/users/login-form.php', $data, 'clean');
  }

  public function viewProfile() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'User Profile',
      'user' => $user,
    ];

    $this->render('resources/views/users/view-profile.php', $data);
  }

  public function editForm() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'Edit Profile',
      'user' => $user,
    ];

    $this->render('resources/views/users/edit-form.php', $data);
  }

  public function register() {
    $hashed_password = $this->model->hashPassword($_POST['password']);

    $registrationData = new UserData(
      name: $_POST['name'],
      email: $_POST['email'],
      passwordHash: $hashed_password,
      role: $_POST['role']
    );

    try {
      $this->model->create($registrationData);
      $this->navigate('/users/login');
    } catch (\Exception $e) {
      return $this->throwViewError('resources/views/users/register-form.php', $e);
    }
  }

  public function login() {
    try {
      $this->model->login($_POST['email'], $_POST['password']);
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      return $this->throwViewError('resources/views/users/login-form.php', $e);
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
    $user = $this->model->get($userId);

    $profileData = new UserData(
      name: $_POST['name'],
      email: $_POST['email'],
      passwordHash: $user['password_hash'],
      role: $user['role'],
    );

    try {
      $this->model->update($userId, $profileData);
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      return $this->throwViewError('resources/views/users/edit-form.php', $e);
    }
  }

  public function delete() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();

    $this->model->delete($userId);
    session_destroy();

    $this->navigate('/');
  }
}
