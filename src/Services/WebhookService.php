<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Services;

use Souzajluiz\Ekwanza\Config;
use Souzajluiz\Ekwanza\DTO\PaymentNotification;
use Souzajluiz\Ekwanza\Exceptions\InvalidSignatureException;
use Souzajluiz\Ekwanza\Security\SignatureValidator;

class WebhookService
{
    public function __construct(private readonly Config $config) {}

    /**
     * Parse and verify a Payment Callback Webhook.
     *
     * @param array $payload The JSON decoded webhook body
     * @param string $signature The signature from the x-signature header
     * @throws InvalidSignatureException if the signature doesn't match
     * @return PaymentNotification
     */
    public function verifyPaymentCallback(array $payload, string $signature): PaymentNotification
    {
        $notification = PaymentNotification::fromArray($payload);

        $fields = [
            $notification->code,
            $notification->operationCode,
            $this->config->getMerchantRegistrationNumber(),
            $this->config->getNotificationToken(),
        ];

        if (!SignatureValidator::validate($signature, $this->config->getApiKey(), $fields)) {
            throw new InvalidSignatureException("Signature validation failed for Payment Callback.");
        }

        return $notification;
    }

    /**
     * Parse a Gateway Callback Webhook (GPO / Reference).
     * Note: Documentation does not specify a signature for Gateway callbacks.
     *
     * @param array $payload The JSON decoded webhook body
     * @return \Souzajluiz\Ekwanza\DTO\GatewayWebhook
     */
    public function parseGatewayWebhook(array $payload): \Souzajluiz\Ekwanza\DTO\GatewayWebhook
    {
        return \Souzajluiz\Ekwanza\DTO\GatewayWebhook::fromArray($payload);
    }
}
