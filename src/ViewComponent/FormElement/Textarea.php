<?php

class Flink_ViewComponent_FormElement_Textarea extends Flink_ViewComponent_FormElement {

    private $value;

    public function set_value(?string $value = null) {
        $this->value = $value;
        return $this;
    }

    public function get_value() {
        return $this->value;
    }

}