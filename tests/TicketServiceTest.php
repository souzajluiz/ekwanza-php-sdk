<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Souzajluiz\Ekwanza\Config;
use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;
use Souzajluiz\Ekwanza\DTO\Ticket;
use Souzajluiz\Ekwanza\Enums\Environment;
use Souzajluiz\Ekwanza\Services\TicketService;

class TicketServiceTest extends TestCase
{
    public function testCreateTicketReturnsDto()
    {
        $config = new Config(
            apiKey: 'test',
            notificationToken: 'token',
            merchantRegistrationNumber: 'merch',
            environment: Environment::SANDBOX
        );

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getBody')->willReturn(
            Utils::streamFor(json_encode([
                'Code' => 'TCK-123',
                'QRCode' => 'base64str',
                'ExpirationDate' => '2023-12-31T23:59:59Z',
                'Status' => 0
            ]))
        );

        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('post')->willReturn($mockResponse);

        $ticketService = new TicketService($mockHttp, $config);

        $ticket = $ticketService->create(100.0, 'REF-123', '+244900000000');

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals('TCK-123', $ticket->code);
        $this->assertEquals(0, $ticket->status);
    }
}
