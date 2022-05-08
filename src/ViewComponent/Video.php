<?php

class Flink_ViewComponent_Video extends Flink_ViewComponent {

    private $src;

    public function __construct(string $src) {
        $this->src = $src;
    }

}