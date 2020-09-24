<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class Any implements Mapper {
    public static $any;

    public function map(Context $ctx, $value): array {
        return $ctx->value($value);
    }
}

Any::$any = new Any;
