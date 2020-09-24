<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class JVTestCase extends TestCase {
    protected function assertMapSuccess(array $value) {
        $this->assertNull($value[1]);
        return $value[0];
    }

    protected function assertMapError(array $value) {
        $this->assertNull($value[0]);
        $this->assertEquals('string', gettype($value[1]));
        return $value[1];
    }
}