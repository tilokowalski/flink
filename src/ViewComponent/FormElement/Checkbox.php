<?php

class Flink_ViewComponent_FormElement_Checkbox extends Flink_ViewComponent_FormElement_Input {

    public $type;

    public $checked;

    public function __construct(string $name, ?string $label = '', ?bool $required = false, ?bool $checked = false) {
        $this->set_type('checkbox');
        $this->set_inline();
        $this->checked = $checked;
        parent::__construct($name, $required, $label);
    }

}