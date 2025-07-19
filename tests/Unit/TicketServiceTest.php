<?php

namespace Tests\Unit;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Models\EventModel;
use App\Models\TicketModel;
use App\Services\TicketService;
use App\Utils\GeralUtils;
use PHPUnit\Framework\TestCase;

class TicketServiceTest extends TestCase
{
  /** @var TicketModel&\PHPUnit\Framework\MockObject\MockObject */
  private $ticketModelMock;

  /** @var EventModel&\PHPUnit\Framework\MockObject\MockObject */
  private $eventModelMock;

  private TicketService $ticketService;

  protected function setUp(): void {
    parent::setUp();
    $this->ticketModelMock = $this->createMock(TicketModel::class);
    $this->eventModelMock = $this->createMock(EventModel::class);

    GeralUtils::setEnv('BASE_URL', 'http://localhost:8080');

    $this->ticketService = new TicketService($this->ticketModelMock, $this->eventModelMock);
  }

  public function testReserveTicketSuccess() {
    $this->ticketModelMock->method('getReservedByClient')->with(1, 10)->willReturn(null);
    $this->eventModelMock->method('getById')->with(10)->willReturn(['tickets_available' => 5]);
    $this->ticketModelMock->method('create')->willReturn(100);
    $this->ticketModelMock->method('getById')->with(100)->willReturn(['id' => 100, 'status' => 'reserved']);

    $ticket = $this->ticketService->reserve(1, 10);

    $this->assertEquals('reserved', $ticket['status']);
  }

  public function testReserveTicketWhenAlreadyReserved() {
    $reservedTicket = ['id' => 99, 'status' => 'reserved'];

    $this->ticketModelMock->method('getReservedByClient')->with(1, 10)->willReturn($reservedTicket);
    $this->ticketModelMock->method('getById')->with(99)->willReturn($reservedTicket);

    $ticket = $this->ticketService->reserve(1, 10);

    $this->assertEquals(99, $ticket['id']);
    $this->ticketModelMock->expects($this->never())->method('create');
  }

  public function testReserveTicketNoTicketsAvailable() {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('Nenhum ingresso disponível para este evento.');

    $this->ticketModelMock->method('getReservedByClient')->willReturn(null);
    $this->eventModelMock->method('getById')->willReturn(['tickets_available' => 0]);

    $this->ticketService->reserve(1, 10);
  }

  public function testPurchaseTicketSuccess() {
    $reservedTicket = ['id' => 100];

    $this->ticketModelMock->method('getReservedByClient')->willReturn(null);
    $this->eventModelMock->method('getById')->willReturn(['tickets_available' => 1]);
    $this->ticketModelMock->method('create')->willReturn($reservedTicket['id']);
    $this->ticketModelMock->method('getById')->willReturn($reservedTicket);

    $this->ticketModelMock->expects($this->once())
      ->method('updateStatus')
      ->with($reservedTicket['id'], 'purchased');

    $ticketId = $this->ticketService->purchase(1, 10);
    $this->assertEquals($reservedTicket['id'], $ticketId);
  }

  public function testExpireReservationSuccess() {
    $reservedTicket = ['id' => 100, 'status' => 'reserved'];

    $this->ticketModelMock->method('getReservedByClient')->with(1, 10)->willReturn($reservedTicket);

    $this->ticketModelMock->expects($this->once())
      ->method('updateStatus')
      ->with(100, 'expired');

    $this->ticketService->expireReservation(1, 10);
  }

  public function testExpireReservationNotFound() {
    $this->expectException(NotFoundException::class);
    $this->ticketModelMock->method('getReservedByClient')->willReturn(null);
    $this->ticketService->expireReservation(1, 10);
  }
}

namespace App\Utils;
if (!function_exists('App\Utils\GeralUtils::setEnv')) {
  class GeralUtils {
    private static $env = [];
    public static function setEnv($key, $value) { self::$env[$key] = $value; }
    public static function getEnv($key) { return self::$env[$key] ?? $_ENV[$key]; }
    public static function basePath(string $path): string { return __DIR__ . '/../../../' . $path; }
  }
}
