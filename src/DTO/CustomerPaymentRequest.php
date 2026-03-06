<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\DTO;

class CustomerPaymentRequest
{
    public function __construct(
        public readonly string $mobileNumber,
        public readonly string $token,
        public readonly string $amount,
        public readonly string $operationCode
    ) {}

    public function toArray(): array
    {
        return [
            'mobileNumber' => $this->mobileNumber,
            'token' => $this->token,
            'amount' => $this->amount,
            'operationCode' => $this->operationCode,
        ];
    }
}
