<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Security;

class SignatureValidator
{
    public static function validate(string $generated, string $received): bool
    {
        return hash_equals($generated, $received);
    }
}
