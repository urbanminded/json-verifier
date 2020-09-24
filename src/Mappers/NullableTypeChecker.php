<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class NullableTypeChecker implements Mapper {
    private $parent;

    public function __construct(Mapper $parent) {
        $this->parent = $parent;
    }

    public function map(Context $ctx, $value): array {
        if ($value === null) {
            return $ctx->value(null);
        }
        return $this->parent->map($ctx, $value);
    }
}
