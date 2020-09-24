<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class NullTypeChecker implements Mapper {
    public function map(Context $ctx, $value): array {
        if ($value !== null) {
            return $ctx->error("expected: null");
        }
        return $ctx->value(null);
    }
}
