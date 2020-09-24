<?php
namespace JSONVerifier\Mappers;

class ObjectTypeCheckerTest extends \JVTestCase {
    protected function setUp(): void {
        $this->ctx = new \JSONVerifier\Context();
    }

    public function testBasicOperation() {
        $cases = [
            [ [], true ],
            [ ['a' => 1, 'b' => 2], true ],
            [ new \stdClass, true ],
            [ [1, 2, 3], false ],
            [ "bar", false ],
            [ null, false ],
            [ 101, false ]
        ];

        foreach ($cases as list($input, $ok)) {
            $m = new ObjectTypeChecker();
            $r = $m->map($this->ctx, $input);
            if ($ok) {
                $v = $this->assertMapSuccess($r);
                $this->assertEquals($v, $input);
            } else {
                $this->assertMapError($r);
            }
        }
    }

    public function testBasicOperationWithSchema() {
        $i = new \stdClass;

        $schema = [
            'name' => [new SimpleTypeChecker(['string']), false],
            'age' => [new SimpleTypeChecker(['integer']), true]
        ];

        $cases = [
            [
                // OK
                ['name' => 'Jason', 'age' => 1], true
            ],
            [
                // OK - age is optional field
                ['name' => 'Jason'], true
            ],
            [
                // OK - extra fields are allowed in non-strict mode
                ['name' => 'Jason', 'pets' => ['ruby', 'byron', 'dora', 'mitzi']], true
            ],
            [
                // Error - required property missing
                ['age' => 1], false
            ],
            [
                // Error - property mapper fails
                ['name' => 123], false
            ],
            [
                // Error - property mapper fails
                ['age' => 'get off my lawn'], false
            ]
        ];

        foreach ($cases as list($input, $ok)) {
            $m = new ObjectTypeChecker($schema, false);
            $r = $m->map($this->ctx, $input);
            if ($ok) {
                $v = $this->assertMapSuccess($r);
                $this->assertEquals($v, $input);
            } else {
                $this->assertMapError($r);
            }
        }
    }

    public function testAdditionalKeyIsErrorInStrictMode() {
        $schema = ['name' => [new SimpleTypeChecker(['string']), false]];
        $m = new ObjectTypeChecker($schema, true);
        $r = $m->map($this->ctx, ['name' => 'Jason', 'food' => 'nachos']);
        $this->assertMapError($r);
    }

    public function testPropertyMapperUsesReturnValue() {
        $i = new \stdClass;
        $m = new ObjectTypeChecker([
            'key' => [new Any, false]
        ]);
        $r = $m->map($this->ctx, ['key' => $i]);
        $v = $this->assertMapSuccess($r);
        $this->assertTrue($v['key'] === $i);
    }
}
