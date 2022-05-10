<?php

class Flink_ViewComponent_Form extends Flink_ViewComponent {

    public $form_elements;

    public $method;
    public $action;

    public function __construct(string $method, ?string $action = null) {
        $this->method = $method;
        $this->action = $action;
    }

    public function add_element(Flink_ViewComponent_FormElement $form_element) {
        $this->form_elements[] = $form_element;
        return $this;
    }

}