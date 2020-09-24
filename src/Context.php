<?php declare(strict_types=1);

namespace JSONVerifier;

class Context {
    public function isError(array $v) {
        return isset($v[1]);
    }

    public function error(string $message) {
        return [null, $message];
    }

    public function value($value) {
        return [$value, null];
    }
}
