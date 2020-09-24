<?php declare(strict_types=1);

namespace JSONVerifier;

class Util {
    public static function isArray($val) {
        return is_array($val) && ((count($val) === 0) || array_key_exists(0, $val));
    }

    public static function isObject($val) {
        if ($val instanceof \stdClass) {
            return true;
        }
        return is_array($val) && ((count($val) === 0) || !array_key_exists(0, $val));
    }
}
