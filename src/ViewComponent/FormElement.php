<?php

abstract class Flink_ViewComponent_FormElement extends Flink_ViewComponent {

    public $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

}