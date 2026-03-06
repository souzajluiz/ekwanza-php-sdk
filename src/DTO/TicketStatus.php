<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\DTO;

class TicketStatus
{
    public const PENDING = 0;
    public const PROCESSED = 1;
    public const EXPIRED = 2;
    public const CANCELLED = 3;

    public function __construct(
        public readonly int $status,
        public readonly string $ticketCode,
        public readonly ?string $message = null
    ) {}

    public static function fromArray(array $data, string $ticketCode): self
    {
        return new self(
            (int)($data['Status'] ?? $data['status'] ?? self::PENDING),
            $ticketCode,
            $data['Message'] ?? $data['message'] ?? null
        );
    }
}
