<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Souzajluiz\Ekwanza\Config;
use Souzajluiz\Ekwanza\Exceptions\InvalidSignatureException;
use Souzajluiz\Ekwanza\Security\SignatureGenerator;
use Souzajluiz\Ekwanza\Services\WebhookService;

class WebhookServiceTest extends TestCase
{
    private WebhookService $service;
    private string $apiKey = 'secret-key';
    private string $merchRef = 'MERCH-001';
    private string $notificationToken = 'TOKEN-123';

    protected function setUp(): void
    {
        $config = new Config(
            apiKey: $this->apiKey,
            notificationToken: $this->notificationToken,
            merchantRegistrationNumber: $this->merchRef
        );
        $this->service = new WebhookService($config);
    }

    public function testVerifyPaymentCallbackSuccess()
    {
        $payload = [
            'code' => 'CODE-X',
            'operationCode' => 'OP-123',
            'status' => 'SUCCESS',
            'amount' => 150.50
        ];

        $fields = [
            $payload['code'],
            $payload['operationCode'],
            $this->merchRef,
            $this->notificationToken
        ];

        $signature = SignatureGenerator::generate($this->apiKey, $fields);

        $notification = $this->service->verifyPaymentCallback($payload, $signature);

        $this->assertEquals('CODE-X', $notification->code);
        $this->assertEquals(150.50, $notification->amount);
    }

    public function testVerifyPaymentCallbackThrowsExceptionOnInvalidSignature()
    {
        $payload = [
            'code' => 'CODE-X',
            'operationCode' => 'OP-123',
            'status' => 'SUCCESS',
            'amount' => 150.50
        ];

        $this->expectException(InvalidSignatureException::class);

        $this->service->verifyPaymentCallback($payload, 'invalid-signature-string');
    }
}
