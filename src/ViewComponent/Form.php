<?php

class Flink_ViewComponent_Form extends Flink_ViewComponent {

    const METHODS_VALID = array('POST', 'GET');

    public $form_elements;

    public $method;
    public $action;

    public function __construct(?string $method = 'post', ?string $action = null) {
        $this->set_method($method);
        $this->action = $action;
    }

    public function add_element(Flink_ViewComponent_FormElement $form_element) {
        $this->form_elements[] = $form_element;
        return $this;
    }

    public function set_method(string $method) {
        $method = Flink_String::from($method)->to_upper();
        Flink_Assert::in_array($method, self::METHODS_VALID);
        $this->method = $method;
    }

    public function is_submitted(): bool {
        foreach ($this->form_elements as $form_element) {
            if ($this->method === 'POST' && !isset($_POST[$form_element->name])) return false;
            if ($this->method === 'GET' && !isset($_GET[$form_element->name])) return false;
        }
        return true;
    }

}