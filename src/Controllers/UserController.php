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

    $this->renderView('resources/views/users/register-form.php', $data);
  }

  public function loginForm() {
    $data = [
      'title' => 'Login',
    ];

    $this->renderView('resources/views/users/login-form.php', $data);
  }

  public function viewProfile() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);
    $user['role'] = $user['role'] === 'seller' ? 'Vendedor' : 'Cliente';

    $data = [
      'title' => 'User Profile',
      'user' => $user,
    ];

    $this->renderView('resources/views/users/view-profile.php', $data);
  }

  public function editForm() {
    $this->ensureLoggedIn();

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'Edit Profile',
      'user' => $user,
    ];

    $this->renderView('resources/views/users/edit-form.php', $data);
  }

  public function register() {
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      return $this->throwFormError(
        'Invalid email format.',
        'resources/views/users/register-form.php'
      );
    }

    // Validate password strength
    if (strlen($_POST['password']) < 8) {
      return $this->throwFormError(
        'Password must be at least 8 characters long.',
        'resources/views/users/register-form.php'
      );
    }

    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $registrationData = new UserData(
      name: strip_tags($_POST['name']),
      email: $_POST['email'],
      passwordHash: $hashed_password,
      role: strip_tags($_POST['role'])
    );

    try {
      $this->model->create($registrationData);

      $this->navigate('/users/login');
    } catch (\Exception $e) {
      return $this->throwFormError(
        'Internal server error during registration.',
        'resources/views/users/register-form.php',
        $e
      );
    }
  }

  public function login() {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $this->model->getByEmail($email);

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password_hash'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_role'] = $user['role'];

      $this->navigate('/users/profile');
    } else {
      $this->throwFormError(
        'Invalid email or password.',
        'resources/views/users/login-form.php'
      );
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
    $userRole = SessionUtils::getUserRole();

    // Validate the email input data
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      return $this->throwFormError(
        'Invalid email format.',
        'resources/views/users/edit-form.php'
      );
    }

    $user = $this->model->get($userId);

    $profileData = new UserData(
      name: strip_tags($_POST['name']),
      email: $_POST['email'],
      passwordHash: $user['password_hash'],
      role: $userRole,
    );

    try {
      $this->model->update($userId, $profileData);

      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      return $this->throwFormError(
        'Internal server error during profile update.',
        'resources/views/users/edit-form.php',
        $e
      );
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
