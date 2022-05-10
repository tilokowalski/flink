<?php

abstract class Flink_ViewComponent_FormElement extends Flink_ViewComponent {

    public $label;

    public $name;
    public $required;

    private $form;

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

    public function set_form(Flink_ViewComponent_Form $form) {
        $this->form = $form;
        return $this;
    }

    public function get_form() {
        return $this->form;
    }

    public function get_value() {
        switch ($this->form->method) {
            case 'POST': return $_POST[$this->name]; break;
            case 'GET': return $_GET[$this->name]; break;
            default: throw new Flink_Exception_NotImplemented('no implementation for form method ' . $this->form->method);
        }
    }

}