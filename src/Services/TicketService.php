<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Services;

use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;
use Souzajluiz\Ekwanza\Core\Config;

class TicketService
{
    public function __construct(
        private HttpClientInterface $http,
        private Config $config
    ) {}

    public function create(float $amount, string $referenceCode, string $mobileNumber): array
    {
        $uri = "/Ticket/{$this->config->notificationToken}?amount={$amount}&referenceCode={$referenceCode}&mobileNumber={$mobileNumber}";

        $response = $this->http->post($uri);

        return json_decode((string)$response->getBody(), true);
    }
}
