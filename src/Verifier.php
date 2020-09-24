<?php declare(strict_types=1);

namespace JSONVerifier;

use JSONVerifier\Mappers\Any;
use JSONVerifier\Mappers\ArrayTypeChecker;
use JSONVerifier\Mappers\Mapper;
use JSONVerifier\Mappers\NullTypeChecker;
use JSONVerifier\Mappers\ObjectTypeChecker;
use JSONVerifier\Mappers\SimpleTypeChecker;
use JSONVerifier\Mappers\StringPatternMapper;

class Verifier {
    private $types;

    public static function arrayOf(...$types) {
        return function(Compiler $c) use ($types) {
            return new ArrayTypeChecker($c->compileConstraint($types));
        };
    }

    public function __construct() {
        $this->types = [
            'any'       => new Any(),
            'array'     => new ArrayTypeChecker(),
            'bool'      => new SimpleTypeChecker(['boolean']),
            'float'     => new SimpleTypeChecker(['double']),
            'int'       => new SimpleTypeChecker(['integer']),
            'null'      => new NullTypeChecker(),
            'number'    => new SimpleTypeChecker(['integer', 'double']),
            'object'    => new ObjectTypeChecker(null, false),
            'string'    => new SimpleTypeChecker(['string'])
        ];
    }

    public function lookupNamedMapper(string $name): Mapper {
        $mapper = $this->types[$name] ?? null;
        if (!$mapper) {
            throw new UsageException("unknown named mapper: $name");
        }
        return $mapper;
    }

    public function registerMapper(string $name, Mapper $mapper) {
        if (isset($this->types[$name])) {
            throw new UsageException("duplicate type: $name");
        }
        $this->types[$name] = $mapper;
    }

    public function registerMappedPattern(string $name, string $pattern, callable $cb) {
        $this->registerMapper($name, new StringPatternMapper($pattern, $cb));
    }

    public function verifyObject(array $schema, $value, bool $strictMode = false) {
        $c = new Compiler($this);
        $c->objectStrictMode = $strictMode;
        $m = $c->compileObjectSchema($schema);
        $ctx = new Context();
        $r = $m->map($ctx, $value);
        if ($ctx->isError($r)) {
            throw new \Exception("object verification failed: " . $r[1]);
        }
        return $r[0];
    }
}
