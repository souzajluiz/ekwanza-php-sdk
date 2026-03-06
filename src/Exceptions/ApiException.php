<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    private array $responseBody;

    public function __construct(string $message = "", int $code = 0, array $responseBody = [], Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->responseBody = $responseBody;
    }

    public function getResponseBody(): array
    {
        return $this->responseBody;
    }
}
