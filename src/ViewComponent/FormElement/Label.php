<?php

class Flink_ViewComponent_FormElement_Label extends Flink_ViewComponent_FormElement {

    public $title;
    public $for;

    public function __construct(string $title, string $for) {
        $this->title = $title;
        $this->for = $for;
    }

}