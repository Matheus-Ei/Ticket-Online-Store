<?php

namespace Tests\Unit;

use App\DTOs\UserData;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use App\Models\UserModel;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
  /** @var UserModel&\PHPUnit\Framework\MockObject\MockObject */
  private $userModelMock;

  private UserService $userService;

  protected function setUp(): void {
    parent::setUp();
    $this->userModelMock = $this->createMock(UserModel::class);
    $this->userService = new UserService($this->userModelMock);
  }

  public function testLoginSuccess() {
    $user = [
      'id' => 1,
      'email' => 'test@example.com',
      'password_hash' => password_hash('password123', PASSWORD_DEFAULT)
    ];

    $this->userModelMock->method('getByEmail')->with('test@example.com')->willReturn($user);

    $result = $this->userService->login('test@example.com', 'password123');
    $this->assertEquals($user, $result);
  }

  public function testLoginInvalidCredentials() {
    $this->expectException(UnauthorizedException::class);
    $this->userModelMock->method('getByEmail')->willReturn(null); 
    $this->userService->login('wrong@example.com', 'password123');
  }

  public function testLoginWrongPassword() {
    $this->expectException(UnauthorizedException::class);

    $user = [
      'id' => 1,
      'email' => 'test@example.com',
      'password_hash' => password_hash('password123', PASSWORD_DEFAULT)
    ];

    $this->userModelMock->method('getByEmail')->with('test@example.com')->willReturn($user);
    $this->userService->login('test@example.com', 'wrongpassword');
  }

  public function testCreateUserSuccess() {
    $userData = new UserData('Test User', 'new@example.com', 'client', 'password123');

    $this->userModelMock->method('getByEmail')->with('new@example.com')->willReturn(null);
    $this->userModelMock->expects($this->once())->method('create')->willReturn(1);

    $userId = $this->userService->create($userData);
    $this->assertEquals(1, $userId);
    $this->assertNotEquals('password123', $userData->password); 
  }

  public function testCreateUserEmailAlreadyExists() {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage("Email já cadastrado");

    $userData = new UserData('Test User', 'exists@example.com', 'client', 'password123');

    $this->userModelMock->method('getByEmail')
      ->with('exists@example.com')
      ->willReturn(['id' => 1, 'email' => 'exists@example.com']);

    $this->userService->create($userData);
  }
}
