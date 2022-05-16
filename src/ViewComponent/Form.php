<?php

class Flink_ViewComponent_Form extends Flink_ViewComponent {

    const METHODS_VALID = array('POST', 'GET');

    private $form_elements = [];

    private $method;
    private $action;

    private $submitable = true;
    private $cancelable = true;

    private $submit_title = 'Senden';
    private $cancel_title = 'Abbrechen';

    public function __construct(?string $method = 'post', ?string $action = null) {
        $this->set_method($method);
        $this->action = $action;
    }


    public function get_form_elements() {
        return $this->form_elements;
    }

    public function get_method() {
        return $this->method;
    }

    public function get_action() {
        return $this->action;
    }

    public function is_submitable() {
        return $this->submitable;
    }

    public function is_cancelable() {
        return $this->cancelable;
    }


    public function set_method(string $method): self {
        $method = Flink_String::from($method)->to_upper();
        Flink_Assert::in_array($method, self::METHODS_VALID, 'method ' . $method . ' is not an implemented form method');
        $this->method = $method;
        return $this;
    }

    public function set_submitable(?bool $submitable = true): self {
        $this->submitable = $submitable;
        return $this;
    }

    public function set_cancelable(?bool $cancelable = true): self {
        $this->cancelable = $cancelable;
        return $this;
    }

    public function add_element(Flink_ViewComponent_FormElement $form_element): self {
        $form_element->set_form($this);
        $this->form_elements[] = $form_element;
        return $this;
    }

    public function is_submitted(): bool {
        foreach ($this->form_elements as $form_element) {
            if ($form_element->get_type() === 'checkbox') continue;
            if ($this->method === 'POST' && !isset($_POST[$form_element->get_name()])) return false;
            if ($this->method === 'GET' && !isset($_GET[$form_element->get_name()])) return false;
        }
        return true;
    }

    public function set_submit_title(string $title): self {
        $this->submit_title = $title;
        return $this;
    }

    public function get_submit_title() {
        return $this->submit_title;
    }

    public function set_cancel_title(string $title): self {
        $this->cancel_title = $title;
        return $this;
    }

    public function get_cancel_title() {
        return $this->cancel_title;
    }

}