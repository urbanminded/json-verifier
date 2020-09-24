<?php
namespace JSONVerifier\Mappers;

class CallbackMapperTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new \JSONVerifier\Context();
    }

    public function testReturnsCallbackValue() {
        $m = new CallbackMapper(function($ctx, $val) {
            return $ctx->value($val * 2);
        });

        $r = $m->map($this->ctx, 4);
        $v = $this->assertMapSuccess($r);
        $this->assertEquals(8, $v);
    }
}
