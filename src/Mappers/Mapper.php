<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

interface Mapper {
    public function map(Context $ctx, $value): array;
}
