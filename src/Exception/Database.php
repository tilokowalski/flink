<?php

namespace Flink\Exception;

class Database extends \Flink\Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'failed to handle database';
        parent::__construct($message);
    }
    
}
