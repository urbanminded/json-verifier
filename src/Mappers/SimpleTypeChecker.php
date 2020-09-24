<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class SimpleTypeChecker implements Mapper {
    private $allowedTypes;

    public function __construct(array $allowedTypes) {
        $this->allowedTypes = $allowedTypes;
    }

    public function map(Context $ctx, $value): array {
        $actualType = gettype($value);
        if (!in_array($actualType, $this->allowedTypes)) {
            return $ctx->error("unexpected type '$actualType', expected: " . implode(', ', $this->allowedTypes));
        }
        return $ctx->value($value);
    }
}
