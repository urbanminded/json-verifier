<?php
namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class NullTypeCheckerTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new Context();
    }

    public function testNullOK() {
        $m = new NullTypeChecker();
        $r = $m->map($this->ctx, null);
        $v = $this->assertMapSuccess($r);
        $this->assertNull($v);
    }

    public function testNotNullFails() {
        $m = new NullTypeChecker();
        $r = $m->map($this->ctx, "foo");
        $this->assertMapError($r);
    }
}
