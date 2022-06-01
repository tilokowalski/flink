<?php

class Flink_ViewComponent_FormElement_GoogleRecaptcha extends Flink_ViewComponent_FormElement {

    private $key;

    public function set_key(string $key): self {
        $this->key = $key;
        return $this;
    }

    public function get_key() {
        return $this->key;
    }

    public function render() {
        Flink_Assert::not_null($this->key, 'recaptcha vc can not be rendered without a key');
        parent::render();
    }

}