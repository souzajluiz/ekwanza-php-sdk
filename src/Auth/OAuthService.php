<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Auth;

use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;
use Souzajluiz\Ekwanza\Exceptions\AuthenticationException;

class OAuthService
{
    public function __construct(
        private HttpClientInterface $http,
        private string $clientId,
        private string $clientSecret,
        private string $resource
    ) {}

    public function getToken(): string
    {
        $response = $this->http->post('/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'resource' => $this->resource
        ]);

        $data = json_decode((string)$response->getBody(), true);

        if (!isset($data['access_token'])) {
            throw new AuthenticationException('OAuth authentication failed');
        }

        return $data['access_token'];
    }
}
