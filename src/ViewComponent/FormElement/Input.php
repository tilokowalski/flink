<?php

class Flink_ViewComponent_FormElement_Input extends Flink_ViewComponent_FormElement {

    public $type;

    public function __construct(string $type, string $name, ?string $label = '', ?bool $required = false) {
        $this->type = $type;
        parent::__construct($name, $required, $label);
    }

}