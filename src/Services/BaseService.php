<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Services;

use Souzajluiz\Ekwanza\Config;
use Souzajluiz\Ekwanza\Contracts\HttpClientInterface;
use Souzajluiz\Ekwanza\Exceptions\ApiException;

abstract class BaseService
{
    public function __construct(
        protected readonly HttpClientInterface $http,
        protected readonly Config $config
    ) {}

    protected function handleResponse(\Psr\Http\Message\ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = (string)$response->getBody();

        $data = json_decode($body, true) ?? [];

        if ($statusCode >= 400) {
            $apiStatus = $data['Status'] ?? $data['status'] ?? null;
            $message = $data['message'] ?? $data['Message'] ?? 'API Error encountered.';

            // E-kwanza returns specific internal error statuses inside the JSON body (e.g. 11, 36)
            // If the message is generic but we have an internal status, we can try to improve the message
            if ($message === 'API Error encountered.' && $apiStatus !== null) {
                $message = "e-Kwanza API Error (Status: {$apiStatus}). ";
                $message .= isset($data['Range']) ? " Range issues found." : "Please check your parameters.";
            }

            throw new ApiException(
                message: $message,
                code: $statusCode,
                responseBody: $data
            );
        }

        return $data;
    }
}