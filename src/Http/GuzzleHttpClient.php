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
        try {
            return $this->client->request('GET', $uri, ['headers' => $headers]);
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
            throw $e;
        }
    }

    public function post(string $uri, array $body = [], array $headers = []): ResponseInterface
    {
        $options = ['headers' => $headers];

        if (strcasecmp($headers['Content-Type'] ?? '', 'application/x-www-form-urlencoded') === 0) {
            $options['form_params'] = $body;
        }
        else {
            $options['json'] = $body;
        }

        try {
            return $this->client->request('POST', $uri, $options);
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
            throw $e;
        }
    }
}