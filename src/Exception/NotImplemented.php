<?php

class Flink_Exception_NotImplemented extends Flink_Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'not implemented';
        parent::__construct($message);
    }
}
