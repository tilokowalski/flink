<?php

abstract class Flink_ViewComponent {

    private function get_component_name() {
        $component = Flink_String::from(get_called_class())->explode('_');
        return Flink_String::from($component[count($component) - 1])->to_lower();
    }

    public function get_component_file() {
        $content_file = 'vendor/tilokowalski/flink/assets/html/vc/' . $this->get_component_name() . '.phtml';
        Flink_Assert::file_exists($content_file, 'missing vc content file: ' . $content_file);
        return $content_file;
    }

    public function render() {
        include_once($this->get_component_file());
    }

}