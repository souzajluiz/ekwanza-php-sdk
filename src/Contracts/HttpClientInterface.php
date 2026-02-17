<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Contracts;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function get(string $uri, array $headers = []): ResponseInterface;
    public function post(string $uri, array $body = [], array $headers = []): ResponseInterface;
}
