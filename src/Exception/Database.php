<?php

class Flink_Exception_Database extends Flink_Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'failed to handle database';
        parent::__construct($message);
    }
}
