<?php

namespace App\Services;

use App\DTOs\TicketData;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use App\Models\TicketModel;
use App\Models\EventModel;
use App\Utils\GeralUtils;
use App\Utils\PdfUtils;
use App\Utils\QrCodeUtils;
use Dompdf\Dompdf;

class TicketService extends AbstractService {
  public function __construct(
    private TicketModel $model,
    private EventModel $eventModel
  ) {}

  public function get($id, $client_id) {
    $ticket = $this->model->getById((int) $id);

    if (!$ticket || $ticket['client_id'] !== $client_id) {
      throw new NotFoundException('Ingresso não encontrado ou não pertence ao usuário.');
    }

    $ticket['qr_code'] = $this->generatePdfQrCode($ticket['id']);

    return $ticket;
  }

  public function generatePdfQrCode ($ticketId) {
    $qrCodeUrl = GeralUtils::getEnv('BASE_URL') . '/tickets/generate-pdf/' . $ticketId;
    $qrCode = QrCodeUtils::generate($qrCodeUrl);
    return $qrCode;
  }

  public function generatePdf(int $ticketId, $clientId) {
    $ticket = $this->model->getById($ticketId);

    if (!$ticket) {
      throw new NotFoundException('Ingresso não encontrado.');
    }

    if ($ticket['status'] !== 'purchased' || $ticket['client_id'] !== $clientId) {
      throw new UnauthorizedException('O ingresso não está comprado. ou não pertence ao usuário.');
    }

    $ticket['qr_code'] = $this->generatePdfQrCode($ticketId);

    $pdf_template = GeralUtils::basePath('resources/views/tickets/ticket-pdf.php');

    PdfUtils::render($pdf_template, "ticket-{$ticketId}.pdf", ['ticket' => $ticket]);
  }

  public function getAll() {
    return $this->model->getAll();
  }

  public function getPurchased(int $clientId) {
    return $this->model->getPurchasedByClient($clientId);
  }

  public function reserve(int $clientId, int $eventId) {
    // Verify if the client already has a reserved ticket for this event
    $reservedTicket = $this->model->getReservedByClient($clientId, $eventId);
    if($reservedTicket) {
      return $this->model->getById($reservedTicket['id']);
    }

    // Verify if the event exists and has available tickets
    $hasTickets = $this->eventModel->getById($eventId)['tickets_available'] > 0;
    if (!$hasTickets) {
      throw new ValidationException(message: 'Nenhum ingresso disponível para este evento.');
    }

    $ticketData = new TicketData(
      status: 'reserved',
      clientId: $clientId,
      eventId: $eventId
    );

    $ticketId = $this->model->create($ticketData);
    return $this->model->getById($ticketId);
  }

  public function purchase(int $clientId, int $eventId) {
    if (!$clientId || !$eventId) {
      throw new ValidationException(message: 'ID do cliente e do evento são obrigatórios.');
    }

    $ticket = $this->reserve($clientId, $eventId);
    $this->model->updateStatus($ticket['id'], 'purchased');

    return $ticket['id'];
  }

  public function expireReservation(int $clientId, int $eventId) {
    $ticket = $this->model->getReservedByClient($clientId, $eventId);

    if (!$ticket || $ticket['status'] !== 'reserved') {
      throw new NotFoundException('Ingresso não encontrado ou não está reservado.');
    }

    $this->model->updateStatus($ticket['id'], 'expired');
  }
}
