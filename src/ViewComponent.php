<?php

abstract class Flink_ViewComponent {

    abstract function get_html();

    public function __toString() {
        return $this->get_html();
    }

    public function render() {
        echo $this->get_html();
    }
    
}
