<?php

namespace Flink\Exception\Entity;

class Flink_Exception_Entity_UnmappedProperty extends Flink_Exception_Entity {

    public function __construct(string $class, string $attribute) {
        $message = $class . '->' . $attribute . ' is neither a property nor a mapped relation';
        parent::__construct($message);
    }
}
