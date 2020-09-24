<?php declare(strict_types=1);

namespace JSONVerifier\Mappers;

use JSONVerifier\Context;
use JSONVerifier\Util;

class ObjectTypeChecker implements Mapper {
    private $schema, $strict;
    
    public function __construct(?array $schema = null, bool $strict = false) {
        $this->schema = $schema;
        $this->strict = $strict;
    }

    public function map(Context $ctx, $value): array {
        if (!Util::isObject($value)) {
            return $ctx->error("value is not an object");
        }

        if (!$this->schema) {
            return $ctx->value($value);
        }
        
        if (is_array($value)) {
            $adapter = new ObjectArrayAdapter($value);
        } else {
            $adapter = new ObjectStdClassAdapter($value);
        }

        $out = [];
        foreach ($this->schema as $key => list($mapper, $optional)) {
            if (!$adapter->propertyExists($key)) {
                if ($optional) {
                    continue;
                }
                return $ctx->error("required key '$key' missing");
            }
            $res = $mapper->map($ctx, $adapter->get($key));
            if ($ctx->isError($res)) {
                return $res;
            }
            $out[$key] = $res[0];
        }

        foreach ($adapter->each() as $k => $v) {
            if (!isset($this->schema[$k])) {
                if ($this->strict) {
                    return $ctx->error("extra key '$k' not allowed in strict mode");
                } else {
                    $out[$k] = $v;
                }
            }
        }

        return $ctx->value($out);
    }
}

interface ObjectAdapter {
    public function propertyExists(string $key): bool;
    public function get(string $key);
    public function each();
}

class ObjectArrayAdapter implements ObjectAdapter {
    private $subject;
    public function __construct(array $subject) { $this->subject = $subject; }
    public function propertyExists(string $key): bool { return array_key_exists($key, $this->subject); }
    public function get(string $key) { return $this->subject[$key]; }
    public function each() { foreach ($this->subject as $k => $v) yield $k => $v; }
}

class ObjectStdClassAdapter implements ObjectAdapter {
    private $subject;
    public function __construct(\stdClass $subject) { $this->subject = $subject; }
    public function propertyExists(string $key): bool { return property_exists($this->subject, $key); }
    public function get(string $key) { return $this->subject->$key; }
    public function each() { foreach ($this->subject as $k => $v) yield $k => $v; }
}
