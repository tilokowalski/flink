<?php

class Flink_Exception_Database_InitiationFailed extends Flink_Exception_Database {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'database initiation failed';
        parent::__construct($message);
    }
}
