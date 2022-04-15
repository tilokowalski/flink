<?php

class Flink_Exception_Database_InvalidQuery extends Flink_Exception_Database {

    public function __construct(?string $query = null) {
        $message = 'provided mysql query contains errors: ';
        if (null !== $query) $message .= '"' . $query . '"';
        parent::__construct($message);
    }
}
