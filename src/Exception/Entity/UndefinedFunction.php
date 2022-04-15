<?php

namespace Flink\Exception\Entity;

class Flink_Exception_Entity_UndefinedFunction extends Flink_Exception_Entity {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'called function is not defined';
        parent::__construct($message);
    }
}
