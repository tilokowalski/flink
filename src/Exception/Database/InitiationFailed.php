<?php

namespace Flink\Exception\Database;

class InitiationFailed extends \Flink\Exception\Database {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'database initiation failed';
        parent::__construct($message);
    }
}
