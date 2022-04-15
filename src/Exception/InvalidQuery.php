<?php

namespace Flink\Exception;

class Flink_Exception_InvalidQuery extends Flink_Exception {

    public function __construct(?string $query = null) {
        $message = 'provided mysql query contains errors: ';
        if (null !== $query) $message .= '"' . $query . '"';
        parent::__construct($message);
    }
}
