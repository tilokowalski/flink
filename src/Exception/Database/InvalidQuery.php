<?php

namespace Flink\Exception\Database;

class InvalidQuery extends \Flink\Exception\Database {

    public function __construct(?string $query = null) {
        $message = 'provided mysql query contains errors: ';
        if (null !== $query) $message .= '"' . $query . '"';
        parent::__construct($message);
    }
}
