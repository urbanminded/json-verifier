<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;
use JSONVerifier\Util;

class ArrayTypeChecker implements Mapper {
    private $elementMapper;
    
    public function __construct(?Mapper $elementMapper = null) {
        $this->elementMapper = $elementMapper;
    }

    public function map(Context $ctx, $value): array {
        if (!Util::isArray($value)) {
            return $ctx->error("value is not an array");
        }

        if (!$this->elementMapper) {
            return $ctx->value($value);
        }

        $out = [];
        foreach ($value as $el) {
            $r = $this->elementMapper->map($ctx, $el);
            if ($ctx->isError($r)) {
                return $r;
            }
            $out[] = $r[0];
        }

        return $ctx->value($out);
    }
}
