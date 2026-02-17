<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Core;

use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;
use Souzajluiz\Ekwanza\Http\GuzzleHttpClient;
use Souzajluiz\Ekwanza\Services\TicketService;

class EkwanzaClient
{
    private HttpClientInterface $http;

    public function __construct(private Config $config)
    {
        $this->http = new GuzzleHttpClient($config->baseUrl, $config->timeout);
    }

    public function tickets(): TicketService
    {
        return new TicketService($this->http, $this->config);
    }
}
