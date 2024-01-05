<?php

namespace Flink\Exception;

class Entity extends \Flink\Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'failed to handle entity';
        parent::__construct($message);
    }
    
}
