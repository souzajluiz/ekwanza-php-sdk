<?php
declare(strict_types=1);

namespace Souzajluiz\Ekwanza\Enums;

enum Environment:string {
    case SANDBOX = 'sandbox';
    case PRODUCTION = 'production';
}
