<?php
namespace JSONVerifier\Mappers;

class ArrayTypeCheckerTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new \JSONVerifier\Context();
    }

    public function testBasicOperation() {
        $cases = [
            [ [], true ],
            [ [1,2,3], true ],
            [ ["foo", 123, true, null], true ],
            [ "bar", false ],
            [ null, false ],
            [ 101, false ]
        ];

        foreach ($cases as list($input, $ok)) {
            $m = new ArrayTypeChecker();
            $r = $m->map($this->ctx, $input);
            if ($ok) {
                $v = $this->assertMapSuccess($r);
                $this->assertEquals($v, $input);
            } else {
                $this->assertMapError($r);
            }
        }
    }

    public function testSuccessWithElementMapper() {
        $m = new ArrayTypeChecker(new SimpleTypeChecker(['integer']));
        $r = $m->map($this->ctx, [1,2,3]);
        $v = $this->assertMapSuccess($r);
        $this->assertEquals([1,2,3], $v);
    }

    public function testErrorWithElementMapper() {
        $m = new ArrayTypeChecker(new SimpleTypeChecker(['integer']));
        $r = $m->map($this->ctx, [1,2,"xyzzy"]);
        $this->assertMapError($r);
    }

    public function testElementMapperUsesReturnValue() {
        $i = new \stdClass;
        $m = new ArrayTypeChecker(new Any());
        $r = $m->map($this->ctx, [$i]);
        $v = $this->assertMapSuccess($r);
        $this->assertTrue($v[0] === $i);
    }
}
