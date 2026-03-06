<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Security;

class SignatureGenerator
{
    /**
     * Generate an HMAC-SHA256 signature from an array of fields.
     * The fields MUST be passed in the exact order required by the specific endpoint/webhook.
     *
     * @param string $apiKey The merchant API key
     * @param array<int, string|float|int> $fields Ordered array of values to concatenate
     * @return string The base64 encoded HMAC-SHA256 signature
     */
    public static function generate(string $apiKey, array $fields): string
    {
        $payload = implode('', $fields);
        
        // The e-kwanza API documentation typically uses base64 encoded HMAC-SHA256. 
        // Returning raw binary (true) and base64 encoding it is the standard for .NET/Java backends.
        $hash = hash_hmac('sha256', $payload, $apiKey, true);
        
        return base64_encode($hash);
    }
}
