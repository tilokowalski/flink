<?php

class Flink_ViewComponent_FormElement_Submit extends Flink_ViewComponent_FormElement {

    public $title;

    public function __construct(string $title) {
        $this->title = $title;
    }

}