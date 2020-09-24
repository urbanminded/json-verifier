<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class CallbackMapper implements Mapper {
    private $callback;

    public function __construct(callable $callback) {
        $this->callback = $callback;
    }

    public function map(Context $ctx, $value): array {
        return ($this->callback)($ctx, $value);
    }
}
