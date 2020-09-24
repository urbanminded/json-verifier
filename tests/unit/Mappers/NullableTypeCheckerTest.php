<?php
namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class NullableTestMapper implements Mapper {
    private $val;
    public function __construct($val) { $this->val = $val; }
    public function map(Context $ctx, $value): array { return $ctx->value($this->val); }
}

class NullableTypeCheckerTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new Context();
    }

    public function testReturnsNullWhenNull() {
        $m = new NullableTypeChecker(new SimpleTypeChecker(['string']));
        $r = $m->map($this->ctx, null);
        $v = $this->assertMapSuccess($r);
        $this->assertNull($v);
    }

    public function testReturnsParentWhenNotNull() {
        $i = new \stdClass;
        $m = new NullableTypeChecker(new NullableTestMapper($i));
        $r = $m->map($this->ctx, 'ignore this');
        $v = $this->assertMapSuccess($r);
        $this->assertTrue($v === $i);
    }
}
