<?php

class Flink_ViewComponent_FormElement_Input extends Flink_ViewComponent_FormElement {

    private $type;
    private $checked = false;
    private $prefilled_value;

    public static function text(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('text');
    }

    public static function email(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('email');
    }

    public static function phone(string $name, ?string $title = null, ?bool $required = false): self {
        $result = new self($name, $title, $required);
        return $result->set_type('phone');
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

    public function set_prefilled_value(?string $prefilled_value = null) {
        Flink_Assert::not_equals($this->get_type(), 'checkbox', 'input of type checkbox is to be handled with set_checked');
        $this->prefilled_value = $prefilled_value;
        return $this;
    }

    public function get_prefilled_value() {
        return $this->prefilled_value;
    }

    public function set_checked(?bool $checked = true): self {
        Flink_Assert::equals($this->get_type(), 'checkbox', 'input of type ' . $this->get_type() . ' can not be \'checked\'');
        $this->checked = $checked;
        return $this;
    }

    public function get_checked() {
        return $this->checked;
    }

    public function is_checked() {
        Flink_Assert::equals($this->get_type(), 'checkbox', 'input of type ' . $this->get_type() . ' can not be \'checked\'');
        return $this->get_value() === 'on';
    }

}