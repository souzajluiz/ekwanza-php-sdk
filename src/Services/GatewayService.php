<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Services;

use Souzajluiz\Ekwanza\Exceptions\ApiException;

class GatewayService extends BaseService
{
    private const OAUTH_URL_TEMPLATE = 'https://login.microsoftonline.com/appypaydev.onmicrosoft.com/oauth2/token';
    private ?string $accessToken = null;

    /**
     * Authenticate and get a gateway token.
     * This is an internal method called before interacting with Gateway endpoints.
     */
    public function authenticate(): string
    {
        if ($this->accessToken !== null) {
            return $this->accessToken; // Basic caching in memory
        }

        $uri = self::OAUTH_URL_TEMPLATE;

        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->config->getClientId() ?? '',
            'client_secret' => $this->config->getClientSecret() ?? '',
            'resource' => $this->config->getResource() ?? '',
        ];

        // Format as form_params for OAuth
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        // This is a bit of a workaround because our standard HTTP interface accepts JSON body by default
        // In a more robust implementation, the HttpClientInterface would accept form params or multipart options
        // Assuming the HttpClient implementation here supports JSON for now, or we can improve it later.
        $response = $this->http->post($uri, $body, $headers);
        $data = $this->handleResponse($response);

        if (!isset($data['access_token'])) {
            throw new ApiException("Authentication failed: 'access_token' not found in response.");
        }

        $this->accessToken = $data['access_token'];

        return $this->accessToken;
    }

    /**
     * Create a charge on the Gateway.
     *
     * @param string $tenantId The Azure tenant ID for auth
     * @param float $amount The amount to charge
     * @param string $merchantTransactionId The merchant transaction unique ID
     * @param string $paymentMethod The payment method (e.g. MULTICAIXA)
     * @param string|null $description Optional description
     * @return array
     */
    public function createCharge(
        string $tenantId,
        float $amount,
        string $merchantTransactionId,
        string $paymentMethod,
        ?string $description = null,
        ?array $paymentInfo = null
        ): array
    {
        $token = $this->authenticate();

        $uri = sprintf("%s/charges", $this->config->getGatewayUrl());

        $headers = [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Cookie' => [
                'ARRAffinity=61d869b39c80b800fa66bdafa3089846c090ff86f5d67f887aa34253e56405fb',
                'ARRAffinitySameSite=61d869b39c80b800fa66bdafa3089846c090ff86f5d67f887aa34253e564'
            ]
        ];

        $payload = [
            'amount' => $amount,
            'currency' => 'AOA',
            'merchantTransactionId' => $merchantTransactionId,
            'paymentMethod' => 'REF_bfeeb4b9-31d4-4030-aed4-a204ac19163e',
            'options' => [
                'MerchantIdentifier' => $this->config->getMerchantRegistrationNumber(),
                'ApiKey' => $this->config->getApiKey(),
            ]
        ];

        if ($description) {
            $payload['description'] = $description;
        }

        if ($paymentInfo !== null) {
            $payload['paymentInfo'] = $paymentInfo;
        }

        $response = $this->http->post($uri, $payload, $headers);

        dd($response, $uri, $payload, $headers);

        return $this->handleResponse($response);
    }
}