<?php

namespace App\Controllers;

class UserController extends AbstractController {
  public function registerForm() {
    // GET Render the registration form view
    // Permissions: Public
    
    $data = [
      'title' => 'Register',
    ];

    $this->renderView('resources/views/users/register-form.php', $data);
  }

  public function loginForm() {
    // GET Render the login form view
    // Permissions: Public

    $data = [
      'title' => 'Login',
    ];

    $this->renderView('resources/views/users/login-form.php', $data);
  }

  public function viewProfile() {
    // GET Render the user's profile view
    // Permissions: Owner client or user

    $data = [
      'title' => 'User Profile',
    ];

    $this->renderView('resources/views/users/view-profile.php', $data);
  }

  public function editProfileForm() {
    // GET Render the form to edit user profile
    // Permissions: Owner client or user
    
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
