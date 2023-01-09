<?php

namespace Flink\Exception\Entity;

class UndefinedFunction extends \Flink\Exception\Entity {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'called function is not defined';
        parent::__construct($message);
    }
}
