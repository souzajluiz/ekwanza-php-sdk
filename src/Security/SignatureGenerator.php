<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Security;

class SignatureGenerator
{
    public static function generate(string $payload, string $key): string
    {
        return hash_hmac('sha256', $payload, $key);
    }
}
