<?php

namespace Flink\Exception;

class Flink_Exception_AssertionFailed extends Flink_Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'assertion failed';
        parent::__construct($message);
    }
}
