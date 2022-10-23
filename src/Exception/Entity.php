<?php

class Flink_Exception_Entity extends Delight_Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'failed to handle entity';
        parent::__construct($message);
    }
}
