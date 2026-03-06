<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\DTO;

class GatewayWebhook
{
    public const STATUS_SUCCESS = 1;
    public const STATUS_CANCELLED = 3;
    public const STATUS_FAILED = 4;
    public const STATUS_ERROR = 5;

    public function __construct(
        public readonly string $merchantTransactionId,
        public readonly int $ekwanzaTransactionId,
        public readonly int $operationStatus,
        public readonly float $amount,
        public readonly string $merchantIdentifier,
        public readonly string $referenceType
    ) {}

    public static function fromArray(array $data): self
    {
        $operationData = $data['operationData'] ?? [];
        
        return new self(
            $data['merchantTransactionId'] ?? '',
            (int)($data['ekwanzaTransactionId'] ?? 0),
            (int)($data['operationStatus'] ?? 0),
            (float)($operationData['amount'] ?? 0),
            $operationData['merchantIdentifier'] ?? '',
            $operationData['referenceType'] ?? ''
        );
    }
}
