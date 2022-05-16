<?php

class Flink_ViewComponent_FormElement_Checkbox extends Flink_ViewComponent_FormElement_Input {

    private $checked;

    public function __construct(string $name, ?string $title = null, ?bool $required = false, ?bool $checked = false) {
        $this->set_checked($checked);
        parent::__construct($name, $title, $required);
    }

    public function set_checked(?bool $checked = true) {
        $this->checked = $checked;
    }

    public function get_checked() {
        return $this->checked;
    }

    public function is_checked() {
        return $this->get_value() === 'on';
    }

}