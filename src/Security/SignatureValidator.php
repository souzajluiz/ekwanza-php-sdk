<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Security;

class SignatureValidator
{
    /**
     * Validade a given signature against the expected signature generated from fields.
     *
     * @param string $expectedSignature The expected signature (e.g., from the webhook header)
     * @param string $apiKey The merchant API key
     * @param array<int, string|float|int> $fields Ordered array of values to build the signature
     * @return bool True if signatures match, false otherwise
     */
    public static function validate(string $expectedSignature, string $apiKey, array $fields): bool
    {
        $generatedSignature = SignatureGenerator::generate($apiKey, $fields);
        
        // Use hash_equals to prevent timing attacks
        return hash_equals($generatedSignature, $expectedSignature);
    }
}
