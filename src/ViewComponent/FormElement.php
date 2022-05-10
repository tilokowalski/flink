<?php

abstract class Flink_ViewComponent_FormElement extends Flink_ViewComponent {

    public $label;

    public $name;
    public $required;

    public function __construct(string $label, string $name, bool $required) {
        $this->label = new Flink_ViewComponent_FormElement_Label($label, $name);
        $this->name = $name;
        $this->required = $required;
    }

}