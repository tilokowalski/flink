<?php

class Flink_ViewComponent_FormElement_Input extends Flink_ViewComponent {

    public $type;

    public function __construct(string $type, string $name) {
        $this->type = $type;
        parent::__construct($name);
    }

}