<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;

class GuzzleHttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct(string $baseUrl, int $timeout = 30)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $timeout
        ]);
    }

    public function get(string $uri, array $headers = []): ResponseInterface
    {
        return $this->client->request('GET', $uri, ['headers' => $headers]);
    }

    public function post(string $uri, array $body = [], array $headers = []): ResponseInterface
    {
        return $this->client->request('POST', $uri, [
            'headers' => $headers,
            'json' => $body
        ]);
    }
}
