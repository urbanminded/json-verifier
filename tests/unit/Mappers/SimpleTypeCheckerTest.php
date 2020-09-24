<?php
namespace JSONVerifier\Mappers;

class SimpleTypeCheckerTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new \JSONVerifier\Context();
    }

    public function testSingleOK() {
        $m = new SimpleTypeChecker(['string']);
        $r = $m->map($this->ctx, "foo");
        $val = $this->assertMapSuccess($r);
        $this->assertEquals("foo", $val);
    }

    public function testMultiOK() {
        $m = new SimpleTypeChecker(['string', 'integer', 'double']);
        
        $r = $m->map($this->ctx, "foo");
        $val = $this->assertMapSuccess($r);
        $this->assertEquals("foo", $val);

        $r = $m->map($this->ctx, 123);
        $val = $this->assertMapSuccess($r);
        $this->assertEquals(123, $val);

        $r = $m->map($this->ctx, 2.5);
        $val = $this->assertMapSuccess($r);
        $this->assertEquals(2.5, $val);
    }

    public function testSingleFail() {
        $m = new SimpleTypeChecker(['integer']);
        $r = $m->map($this->ctx, "foo");
        $val = $this->assertMapError($r);    
    }

    public function testMultiFail() {
        $m = new SimpleTypeChecker(['integer', 'string', 'double']);
        $r = $m->map($this->ctx, true);
        $val = $this->assertMapError($r);
    }
}
