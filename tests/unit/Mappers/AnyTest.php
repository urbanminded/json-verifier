<?php
namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class AnyTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new Context();
    }

    public function testReturnsInput() {
        $inputs = [
            new \stdClass,
            "foo",
            123,
            3.14,
            true,
            [ 1, 2, 3 ],
            [ 'a' => 1, 'b' => 2 ],
            [],
            null
        ];
        foreach ($inputs as $i) {
            $i = new \stdClass;
            $m = new Any();
            $r = $m->map($this->ctx, $i);
            $v = $this->assertMapSuccess($r);
            $this->assertEquals($i, $v);
        }
    }
}
