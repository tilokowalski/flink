<?php

class Flink_ViewComponent_Form extends Flink_ViewComponent {

    public $elements;
    public $method;
    public $action;

    public function __construct(string $method, ?string $action = null) {
        $this->method = $method;
        $this->action = $action;
    }

}