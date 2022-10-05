<?php

class Flink_ViewComponent_FormElement_Textarea extends Flink_ViewComponent_FormElement {

    private $prefilled_value;

    public function set_prefilled_value(?string $prefilled_value = null) {
        $this->prefilled_value = $prefilled_value;
        return $this;
    }

    public function get_prefilled_value() {
        return $this->prefilled_value;
    }

}