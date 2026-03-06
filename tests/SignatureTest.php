<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Souzajluiz\Ekwanza\Security\SignatureGenerator;
use Souzajluiz\Ekwanza\Security\SignatureValidator;

class SignatureTest extends TestCase
{
    private string $apiKey = 'test-api-key';

    public function testGenerateSignature()
    {
        $fields = ['2023-10-01T12:00:00Z', '+244900000000', 'token-123', 'OP-001'];
        $signature = SignatureGenerator::generate($this->apiKey, $fields);

        $this->assertIsString($signature);
        $this->assertNotEmpty($signature);
    }

    public function testValidateValidSignature()
    {
        $fields = ['CODE1', 'OP-001', 'MERCH-001', 'TOKEN-123'];
        $signature = SignatureGenerator::generate($this->apiKey, $fields);

        $isValid = SignatureValidator::validate($signature, $this->apiKey, $fields);
        $this->assertTrue($isValid);
    }

    public function testValidateInvalidSignature()
    {
        $fields = ['CODE1', 'OP-001', 'MERCH-001', 'TOKEN-123'];
        $signature = SignatureGenerator::generate('wrong-key', $fields); // Different key

        $isValid = SignatureValidator::validate($signature, $this->apiKey, $fields);
        $this->assertFalse($isValid);
    }
}
