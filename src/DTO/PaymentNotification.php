<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\DTO;

class PaymentNotification
{
    public function __construct(
        public readonly string $code,
        public readonly string $operationCode,
        public readonly string $status,
        public readonly float $amount
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['code'] ?? '',
            $data['operationCode'] ?? '',
            $data['status'] ?? '',
            (float)($data['amount'] ?? 0)
        );
    }
}
