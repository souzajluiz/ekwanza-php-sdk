<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Core;

use Souzajluiz\Ekwanza\Enums\Environment;

class Config
{
    public function __construct(
        public readonly string $baseUrl,
        public readonly string $notificationToken,
        public readonly string $apiKey,
        public readonly string $companyRegistrationNumber,
        public readonly Environment $environment = Environment::PRODUCTION,
        public readonly int $timeout = 30
    ) {}
}
