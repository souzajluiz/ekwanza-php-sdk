<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Services;

use Souzajluiz\Ekwanza\DTO\CustomerPaymentRequest;
use Souzajluiz\Ekwanza\Security\SignatureGenerator;

class CustomerPaymentService extends BaseService
{
    /**
     * Send payment to a customer.
     *
     * @param string $mobileNumber The customer mobile number
     * @param string $amount The amount to send
     * @param string $operationCode A unique operation code
     * @return array The response from the API
     */
    public function sendPayment(string $mobileNumber, string $amount, string $operationCode): array
    {
        $uri = "/Operations/SendToCustomer";
        $timestamp = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.v\Z');
        $notificationToken = $this->config->getNotificationToken();
        
        $fields = [
            $timestamp,
            $mobileNumber,
            $notificationToken,
            $operationCode
        ];

        $signature = SignatureGenerator::generate($this->config->getApiKey(), $fields);

        $payload = [
            'data' => [
                'mobileNumber' => $mobileNumber,
                'token' => $notificationToken,
                'amount' => $amount,
                'operationCode' => $operationCode
            ],
            'meta' => [
                'timestamp' => $timestamp,
                'signature' => $signature
            ]
        ];

        $headers = [
            'x-signature' => $signature,
        ];

        $response = $this->http->post($uri, $payload, $headers);
        
        return $this->handleResponse($response);
    }
}
