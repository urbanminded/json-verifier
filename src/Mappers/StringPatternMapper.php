<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class StringPatternMapper implements Mapper {
    private $pattern;
    private $callback;

    public function __construct(string $pattern, callable $callback) {
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    public function map(Context $ctx, $value): array {
        if (!is_string($value)) {
            return $ctx->error("string pattern mapper expected string, got " . gettype($value));
        }
        $m = null;
        if (!preg_match($this->pattern, $value, $m)) {
            return $ctx->error("pattern match failed");
        }
        return ($this->callback)($ctx, $value, $m);
    }
}
