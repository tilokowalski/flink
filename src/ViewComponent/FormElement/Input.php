<?php

class Flink_ViewComponent_FormElement_Input extends Flink_ViewComponent_FormElement {

    private $type;

    public static function text(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('text');
    }

    public static function email(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('email');
    }

    public static function password(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('password');
    }

    public static function checkbox(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('checkbox');
    }

    public function set_type(string $type): self {
        $this->type = $type;
        return $this;
    }

    public function get_type() {
        if (null === $this->type) $this->set_type('text');
        return $this->type;
    }

}