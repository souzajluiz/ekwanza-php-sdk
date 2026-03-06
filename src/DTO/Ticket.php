<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\DTO;

class Ticket
{
    public function __construct(
        public readonly string $code,
        public readonly ?string $qrCode,
        public readonly ?string $expirationDate,
        public readonly ?int $status
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['Code'] ?? $data['code'] ?? '',
            $data['QRCode'] ?? $data['qrCode'] ?? null,
            $data['ExpirationDate'] ?? $data['expirationDate'] ?? null,
            isset($data['Status']) || isset($data['status']) ? (int)($data['Status'] ?? $data['status']) : null
        );
    }
}
