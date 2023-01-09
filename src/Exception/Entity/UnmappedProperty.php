<?php

namespace Flink\Exception\Entity;

class UnmappedProperty extends \Flink\Exception\Entity {

    public function __construct(string $class, string $attribute) {
        $message = $class . '->' . $attribute . ' is neither a property nor a mapped relation';
        parent::__construct($message);
    }
}
