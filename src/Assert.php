<?php

class Flink_Assert {

    public static function instanceof($object, $class, ?string $message = null) {
        if (!$object instanceof $class) throw new Flink_Exception_AssertionFailed($message);
        return true;
    }

    public static function equals($x, $y, ?string $message = null) {
        if ($x !== $y) throw new Flink_Exception_AssertionFailed($message);
        return true;
    }

    public static function not_equals($x, $y, ?string $message = null) {
        if ($x === $y) throw new Flink_Exception_AssertionFailed($message);
        return true;
    }

    public static function in_array($value, $array, ?string $message = null) {
        if (!in_array($value, $array)) throw new Flink_Exception_AssertionFailed($message);
        return true;
    }

    public static function array_key_exists($key, $array, ?string $message = null) {
        if (!array_key_exists($key, $array)) throw new Flink_Exception_AssertionFailed($message);
        return true;
    }

}
