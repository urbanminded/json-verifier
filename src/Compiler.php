<?php declare(strict_types=1);

namespace JSONVerifier;

use JSONVerifier\Mappers\Mapper;
use JSONVerifier\Mappers\Choice;
use JSONVerifier\Mappers\NullableTypeChecker;
use JSONVerifier\Mappers\ObjectTypeChecker;

class Compiler {
    public $objectStrictMode = false;

    private $verifier;

    public function __construct(Verifier $verifier) {
        $this->verifier = $verifier;
    }

    public function compileConstraint($constraint): Mapper {
        if (is_string($constraint)) {
            return $this->compileStringConstraint($constraint);
        } else if (is_callable($constraint)) {
            return $constraint($this);
        } else if (is_array($constraint)) {
            if (count($constraint) === 0) {
                throw new UsageException("array constraint cannot be empty");
            }
            if (array_key_exists(0, $constraint)) {
                // assume numeric array - choice
                return new Choice(array_map([$this, 'compileConstraint'], $constraint));
            } else {
                // assume assoc. array - object
                return $this->compileObjectSchema($constraint);
            }
        }
    }

    public function compileObjectSchema(array $objectSchema): Mapper {
        $compiled = [];
        foreach ($objectSchema as $property => $constraint) {
            $mapper = null;
            $optional = false;
            if ($property[0] == '?') {
                $optional = true;
                $property = substr($property, 1);
            }
            $compiled[$property] = [
                $this->compileConstraint($constraint),
                $optional
            ];
        }
        return new ObjectTypeChecker($compiled, $this->objectStrictMode);
    }

    public function compileStringConstraint(string $constraint): Mapper {
        $nullable = false;
        if ($constraint[0] == '?') {
            $nullable = true;
            $constraint = substr($constraint, 1);
        }
        $mapper = $this->verifier->lookupNamedMapper($constraint);
        if ($nullable) {
            $mapper = new NullableTypeChecker($mapper);
        }
        return $mapper;
    }
}