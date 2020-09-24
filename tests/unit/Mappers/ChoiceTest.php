<?php
namespace JSONVerifier\Mappers;

use JSONVerifier\Context;

class ChoiceTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new Context();
    }

    public function testReturnsInputWhenConstraintSatisfied() {
        $cases = [
            [ 1, true ],
            [ false, true ],
            [ "hello", true ],
            [ 3.12, false ],
            [ null, false ],
            [ [], false ],
            [ new \stdClass, false ]
        ];

        foreach ($cases as list($value, $ok)) {

            $m = new Choice([
                new SimpleTypeChecker(['integer']),
                new SimpleTypeChecker(['boolean']),
                new SimpleTypeChecker(['string'])
            ]);

            $r = $m->map($this->ctx, $value);
            
            if ($ok) {
                $v = $this->assertMapSuccess($r);
                $this->assertEquals($v, $value);
            } else {
                $this->assertMapError($r);
            }

        }
    }
}
