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
    // GET Render the registration form view
    // Permissions: Public

    if (SessionUtils::isLoggedIn()) {
      $this->navigate('/users/profile');
    }

    $data = [
      'title' => 'Register',
    ];

    $this->renderView('resources/views/users/register-form.php', $data);
  }

  public function loginForm() {
    // GET Render the login form view
    // Permissions: Public

    if (SessionUtils::isLoggedIn()) {
      $this->navigate('/users/profile');
    }

    $data = [
      'title' => 'Login',
    ];

    $this->renderView('resources/views/users/login-form.php', $data);
  }

  public function viewProfile() {
    // GET Render the user's profile view
    // Permissions: Owner client or user

    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
    }

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'User Profile',
      'user' => $user,
    ];

    $this->renderView('resources/views/users/view-profile.php', $data);
  }

  public function editForm() {
    // GET Render the form to edit user profile
    // Permissions: Owner client or user

    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
    }

    $userId = SessionUtils::getUserId();
    $user = $this->model->get($userId);

    $data = [
      'title' => 'Edit Profile',
      'user' => $user,
    ];

    $this->renderView('resources/views/users/edit-form.php', $data);
  }

  public function register() {
    // POST Handle user registration logic
    // Permissions: Public

    if (SessionUtils::isLoggedIn()) {
      $this->navigate('/users/profile');
    }

    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate email with filter_var
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $data = [
        'title' => 'Registration Failed',
        'error' => 'Invalid email format.',
      ];

      $this->renderView('resources/views/users/register-form.php', $data);
      return;
    }

    // Validate and process registration data
    $registrationData = new UserData(
      strip_tags($_POST['name']),
      $_POST['email'],
      $hashed_password,
      strip_tags($_POST['role'])
    );

    try {
      $this->model->create($registrationData);
      $this->navigate('/users/login');
    } catch (\Exception $e) {
      $data = [
        'title' => 'Registration Failed',
        'error' => 'Internal server error or user already exists.',
      ];

      error_log($e->getMessage());

      $this->renderView('resources/views/users/register-form.php', $data);
    }
  }

  public function login() {
    // POST Handle user login logic
    // Permissions: Public

    if (SessionUtils::isLoggedIn()) {
      $this->navigate('/users/profile');
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $this->model->getByEmail($email);

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password_hash'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_role'] = $user['role'];

      // Redirect to the user's profile page
      $this->navigate('/users/profile');
    } else {
      $data = [
        'title' => 'Login Failed',
        'error' => 'Invalid email or password.',
      ];

      $this->renderView('resources/views/users/login-form.php', $data);
    }
  }

  public function logout() {
    // POST Handle user logout logic
    // Permissions: Owner client or user

    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
    }

    session_destroy();
    $this->navigate('/');
  }

  public function edit() {
    // POST Handle the logic to update user profile
    // Permissions: Owner client or user

    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
    }

    $userId = SessionUtils::getUserId();
    $userRole = SessionUtils::getUserRole();

    // Validate the email input data
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $data = [
        'title' => 'Profile Update Failed',
        'error' => 'Invalid email format.',
      ];

      $this->renderView('resources/views/users/edit-form.php', $data);
      return;
    }

    // If password is not provided, keep the existing password
    if (!empty($_POST['password'])) {
      $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
      $user = $this->model->get($userId);
      $hashed_password = $user['password_hash'];
    }

    $profileData = new UserData(
      strip_tags($_POST['name']),
      $_POST['email'],
      $hashed_password,
      $userRole,
    );

    try {
      $this->model->update($userId, $profileData);
      $this->navigate('/users/profile');
    } catch (\Exception $e) {
      $data = [
        'title' => 'Profile Update Failed',
        'error' => 'Internal server error'
      ];

      error_log($e->getMessage());

      $this->renderView('resources/views/users/edit-form.php', $data);
    }
  }

  public function delete() {
    // POST Handle the logic to delete user profile
    // Permissions: Owner client or user

    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
    }

    $userId = SessionUtils::getUserId();
    $result = $this->model->delete($userId);

    if ($result) {
      session_destroy();
      $this->navigate('/');
    }
  }
}
