<?php
namespace JSONVerifier;

class UtilTest extends \JVTestCase {
    public function testIsObject() {
        $cases = [
            [ new \stdClass, true ],
            [ [], true ],
            [ 123, false ],
            [ [0, 1, 2], false ],
            [ ['a' => 1, 'b' => 2], true ]
        ];
        foreach ($cases as list($val, $isObject)) {
            $this->assertEquals($isObject, Util::isObject($val));
        }
    }

    public function testIsArray() {
        $cases = [
            [ new \stdClass, false ],
            [ [], true ],
            [ 123, false ],
            [ [0, 1, 2], true ],
            [ ['a' => 1, 'b' => 2], false ]
        ];
        foreach ($cases as list($val, $isArray)) {
            $this->assertEquals($isArray, Util::isArray($val));
        }
    }
}
