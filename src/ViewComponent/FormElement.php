<?php

abstract class Flink_ViewComponent_FormElement extends Flink_ViewComponent {

    private $form;

    private $title;
    private $required;

    private $inline = true;

    public function __construct(string $name, ?string $title = null, ?bool $required = false) {
        $this->set_title($title);
        $this->set_required($required);
        parent::__construct($name);
    }
    
    public function get_form() {
        return $this->form;
    }

    public function get_title() {
        return $this->title;
    }

    public function is_required() {
        return $this->required;
    }

    public function get_inline() {
        return $this->inline;
    }

    public function set_form(Flink_ViewComponent_Form $form): self {
        $this->form = $form;
        return $this;
    }

    public function set_title(?string $title = null): self {
        $this->title = $title;
        return $this;
    }
    
    public function set_required(?bool $required = true): self {
        $this->required = $required;
        return $this;
    }

    public function set_inline(?bool $inline = true) {
        $this->inline = $inline;
        return $this;
    }

    public function is_set() {
        switch ($this->form->get_method()) {
            case 'POST': return isset($_POST[$this->get_name()]); break;
            case 'GET': return isset($_GET[$this->get_name()]); break;
            default: throw new Flink_Exception_NotImplemented('no implementation for form method ' . $this->form->get_method());
        }
    }

    public function get_value() {
        if (!$this->is_set()) return null;
        switch ($this->form->get_method()) {
            case 'POST': return $_POST[$this->get_name()]; break;
            case 'GET': return $_GET[$this->get_name()]; break;
            default: throw new Flink_Exception_NotImplemented('no implementation for form method ' . $this->form->get_method());
        }
    }

    public function is_empty() {
        return Flink_String::from($this->get_value())->length() > 0;
    }

}