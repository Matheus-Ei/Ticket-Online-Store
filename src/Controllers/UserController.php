<?php

namespace App\Controllers;

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

    $data = [
      'title' => 'User Profile',
    ];

    $this->renderView('resources/views/users/view-profile.php', $data);
  }

  public function editProfileForm() {
    // GET Render the form to edit user profile
    // Permissions: Owner client or user
    
    if (!SessionUtils::isLoggedIn()) {
      $this->navigate('/users/login');
    }

    $data = [
      'title' => 'Edit Profile',
    ];

    $this->renderView('resources/views/users/edit-profile-form.php', $data);
  }

  public function register() {
    // POST Handle user registration logic
    // Permissions: Public
  }

  public function login() {
    // POST Handle user login logic
    // Permissions: Public
  }

  public function editProfile() {
    // POST Handle the logic to update user profile
    // Permissions: Owner client or user
  }

  public function deleteProfile() {
    // POST Handle the logic to delete user profile
    // Permissions: Owner client or user
  }
}
