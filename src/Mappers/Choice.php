<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class Choice implements Mapper {
    private $choices;
    
    public function __construct($choices) {
        $this->choices = $choices;
    }

    public function map(Context $ctx, $value): array {
        foreach ($this->choices as $c) {
            $r = $c->map($ctx, $value);
            if (!$ctx->isError($r)) {
                return $r;
            }
        }
        return $ctx->error("choice constraint not satisfied");
    }
}
