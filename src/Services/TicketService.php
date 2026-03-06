<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Services;

use Souzajluiz\Ekwanza\DTO\Ticket;
use Souzajluiz\Ekwanza\DTO\TicketStatus;

class TicketService extends BaseService
{
    /**
     * Create a new payment ticket.
     *
     * @param float $amount The amount to charge
     * @param string $referenceCode A unique order reference
     * @param string $mobileNumber The customer mobile number
     * @return Ticket
     */
    public function create(float $amount, string $referenceCode, string $mobileNumber): Ticket
    {
        $uri = "/Ticket/{$this->config->getNotificationToken()}?amount={$amount}&referenceCode={$referenceCode}&mobileNumber={$mobileNumber}";

        $response = $this->http->post($uri);
        $data = $this->handleResponse($response);

        return Ticket::fromArray($data);
    }

    /**
     * Get the status of an existing ticket.
     *
     * @param string $ticketCode The ticket code returned on creation
     * @return TicketStatus
     */
    public function status(string $ticketCode): TicketStatus
    {
        $uri = "/Ticket/{$this->config->getNotificationToken()}/{$ticketCode}";

        $response = $this->http->get($uri);
        $data = $this->handleResponse($response);

        return TicketStatus::fromArray($data, $ticketCode);
    }
}
