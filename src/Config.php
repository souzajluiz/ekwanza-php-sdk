<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza;

use Souzajluiz\Ekwanza\Enums\Environment;

class Config
{
    public const DEFAULT_TIMEOUT = 30;
    
    // Default Base URLs
    public const PROD_BASE_URL = 'https://ekz-partnersapi-qa.e-kwanza.ao'; // Hypothetical based on standard e-kwanza, though documentation refers to gateway differently
    public const SANDBOX_BASE_URL = 'https://ekz-partnersapi-qa.e-kwanza.ao'; // Hypothetical
    
    public const PROD_GATEWAY_URL = 'https://gwy-api.appypay.co.ao/v2.0';
    public const SANDBOX_GATEWAY_URL = 'https://gwy-api-tst.appypay.co.ao/v2.0';

    public function __construct(
        private readonly string $apiKey,
        private readonly string $notificationToken,
        private readonly string $merchantRegistrationNumber,
        private readonly Environment $environment = Environment::PRODUCTION,
        private readonly int $timeout = self::DEFAULT_TIMEOUT,
        private readonly ?string $baseUrl = null,
        private readonly ?string $gatewayUrl = null,
        // Gateway Auth credentials
        private readonly ?string $clientId = null,
        private readonly ?string $clientSecret = null,
        private readonly ?string $resource = null,
    ) {}

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getNotificationToken(): string
    {
        return $this->notificationToken;
    }

    public function getMerchantRegistrationNumber(): string
    {
        return $this->merchantRegistrationNumber;
    }

    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getBaseUrl(): string
    {
        if ($this->baseUrl !== null) {
            return $this->baseUrl;
        }

        return $this->environment === Environment::PRODUCTION 
            ? self::PROD_BASE_URL 
            : self::SANDBOX_BASE_URL;
    }

    public function getGatewayUrl(): string
    {
        if ($this->gatewayUrl !== null) {
            return $this->gatewayUrl;
        }

        return $this->environment === Environment::PRODUCTION 
            ? self::PROD_GATEWAY_URL 
            : self::SANDBOX_GATEWAY_URL;
    }
    
    public function getClientId(): ?string
    {
        return $this->clientId;
    }
    
    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }
    
    public function getResource(): ?string
    {
        return $this->resource;
    }
}