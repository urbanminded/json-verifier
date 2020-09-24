<?php
namespace JSONVerifier\Mappers;

class StringPatternMapperTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new \JSONVerifier\Context();
    }

    public function testMatchOK() {
        $instance = new \stdClass;
        $m = new StringPatternMapper('/(\d{4})-(\w{2})/', function($ctx, $string, $matches) use ($instance) {
            $this->assertEquals("1234-UK", $string);
            $this->assertEquals("1234-UK", $matches[0]);
            $this->assertEquals("1234", $matches[1]);
            $this->assertEquals("UK", $matches[2]);
            return $ctx->value($instance);
        });
        $r = $m->map($this->ctx, "1234-UK");
        $v = $this->assertMapSuccess($r);
        $this->assertTrue($v === $instance);
    }

    public function testFailsWithNonString() {
        $m = new StringPatternMapper('/(\d{4})-(\w{2})/', function($ctx, $string, $matches) {
            throw new \Exception("shouldn't get here");
        });
        $r = $m->map($this->ctx, 123);
        $this->assertMapError($r);
    }

    public function testFailsWhenMatchFails() {
        $m = new StringPatternMapper('/(\d{4})-(\w{2})/', function($ctx, $string, $matches) {
            throw new \Exception("shouldn't get here");
        });
        $r = $m->map($this->ctx, "tubular bells");
        $this->assertMapError($r);
    }
}
