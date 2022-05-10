<?php

abstract class Flink_ViewComponent_FormElement extends Flink_ViewComponent {

    public $label;

    public $name;
    public $required;

    public $inline = false;

    public function __construct(string $name, bool $required, ?string $label = null) {
        $this->name = $name;
        $this->required = $required;
        if (null !== $label) {
            $this->label = new Flink_ViewComponent_FormElement_Label($label, $name);
        } else {
            $this->label = null;
        }
    }

    public function set_required(?bool $required = true) {
        $this->required = $required;
        return $this;
    }

    public function set_inline(?bool $inline = true) {
        $this->inline = $inline;
        return $this;
    }

}