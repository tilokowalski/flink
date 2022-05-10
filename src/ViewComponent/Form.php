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
        $form_element->set_form($this);
        $this->form_elements[] = $form_element;
        return $this;
    }

    public function get_elements() {
        $result = array();
        foreach ($this->form_elements as $form_element) {
            if ($form_element instanceof Flink_ViewComponent_FormElement_Submit) continue;
            $result[] = $form_element;
        }
        return $result;
    }

    public function set_method(string $method) {
        $method = Flink_String::from($method)->to_upper();
        Flink_Assert::in_array($method, self::METHODS_VALID, 'method ' . $method . ' is not an implemented form method');
        $this->method = $method;
    }

    public function is_submitted(): bool {
        foreach ($this->get_elements() as $form_element) {
            if ($this->method === 'POST' && !isset($_POST[$form_element->name])) return false;
            if ($this->method === 'GET' && !isset($_GET[$form_element->name])) return false;
        }
        return true;
    }

}