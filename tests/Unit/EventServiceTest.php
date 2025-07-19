<?php

namespace Tests\Unit;

use App\DTOs\EventData;
use App\Exceptions\NotFoundException;
use App\Models\EventModel;
use App\Services\EventService;
use DateTime;
use PHPUnit\Framework\TestCase;

class EventServiceTest extends TestCase
{
  /** @var EventModel&\PHPUnit\Framework\MockObject\MockObject */
  private $eventModelMock;
  private EventService $eventService;

  protected function setUp(): void {
    parent::setUp();
    $this->eventModelMock = $this->createMock(EventModel::class);
    $this->eventService = new EventService($this->eventModelMock);
  }

  public function testGetTicketsSoldAsSeller() {
    $this->eventModelMock->expects($this->once())
      ->method('getTicketsSold')
      ->with(1, 123)
      ->willReturn(['ticket1', 'ticket2']);

    $result = $this->eventService->getTicketsSold(1, 123, 'seller');
    $this->assertCount(2, $result);
  }

  public function testGetTicketsSoldAsClient() {
    $this->eventModelMock->expects($this->never())->method('getTicketsSold');
    $result = $this->eventService->getTicketsSold(1, 456, 'client');
    $this->assertEmpty($result);
  }

  public function testGetWithOwnerSuccess() {
    $event = ['id' => 1, 'created_by' => 123];

    $this->eventModelMock->method('getById')->with(1)->willReturn($event);

    $result = $this->eventService->getWithOwner(1, 123);
    $this->assertEquals($event, $result);
  }

  public function testGetWithOwnerThrowsExceptionWhenNotOwner() {
    $this->expectException(NotFoundException::class);
    $event = ['id' => 1, 'created_by' => 999]; 

    $this->eventModelMock->method('getById')->with(1)->willReturn($event);
    $this->eventService->getWithOwner(1, 123);
  }

  public function testSaveForCreation() {
    $eventData = new EventData('New Event', 123, 100, 50.0, new DateTime());

    $this->eventModelMock->expects($this->once())->method('create')->with($eventData);
    $this->eventModelMock->expects($this->never())->method('update');

    $this->eventService->save($eventData, null);
  }

  public function testSaveForUpdate() {
    $eventData = new EventData('Updated Event', 123, 150, 75.0, new DateTime());

    $this->eventModelMock->expects($this->once())->method('update')->with(1, $eventData);
    $this->eventModelMock->expects($this->never())->method('create');

    $this->eventService->save($eventData, 1);
  }
}
