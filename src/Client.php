<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza;

use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;
use Souzajluiz\Ekwanza\Http\GuzzleHttpClient;
use Souzajluiz\Ekwanza\Services\CustomerPaymentService;
use Souzajluiz\Ekwanza\Services\GatewayService;
use Souzajluiz\Ekwanza\Services\TicketService;
use Souzajluiz\Ekwanza\Services\WebhookService;

/**
 * Main entry point for the e-kwanza SDK.
 */
class Client
{
    private HttpClientInterface $http;
    private ?TicketService $ticketService = null;
    private ?CustomerPaymentService $customerPaymentService = null;
    private ?GatewayService $gatewayService = null;
    private ?WebhookService $webhookService = null;

    /**
     * Initialize the e-kwanza Client.
     *
     * @param Config $config The SDK configuration object.
     * @param HttpClientInterface|null $httpClient Optional custom HTTP client (useful for testing).
     */
    public function __construct(
        private readonly Config $config,
        ?HttpClientInterface $httpClient = null
    ) {
        $this->http = $httpClient ?? new GuzzleHttpClient(
            $this->config->getBaseUrl(),
            $this->config->getTimeout()
        );
    }

    /**
     * Access the Ticket generation and status service.
     */
    public function tickets(): TicketService
    {
        if ($this->ticketService === null) {
            $this->ticketService = new TicketService($this->http, $this->config);
        }

        return $this->ticketService;
    }

    /**
     * Access the Send to Customer payment service.
     */
    public function customers(): CustomerPaymentService
    {
        if ($this->customerPaymentService === null) {
            $this->customerPaymentService = new CustomerPaymentService($this->http, $this->config);
        }

        return $this->customerPaymentService;
    }

    /**
     * Access the Gateway Payments Online (GPO) service.
     */
    public function gateway(): GatewayService
    {
        if ($this->gatewayService === null) {
            // Gateway might require a different base URL, so we can pass it if needed,
            // or the GatewayService can handle instantiating its own HTTP client based on config.
            $gatewayHttp = new GuzzleHttpClient(
                $this->config->getGatewayUrl(),
                $this->config->getTimeout()
            );
            $this->gatewayService = new GatewayService($gatewayHttp, $this->config);
        }

        return $this->gatewayService;
    }

    /**
     * Access the Webhook verification service.
     */
    public function webhooks(): WebhookService
    {
        if ($this->webhookService === null) {
            $this->webhookService = new WebhookService($this->config);
        }

        return $this->webhookService;
    }
}
