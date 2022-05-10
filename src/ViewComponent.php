<?php

abstract class Flink_ViewComponent {

    private function get_component_name() {
        $result = '';
        foreach (Flink_String::from(get_called_class())->explode('_') as $key => $segment) {
            if ($key <= 1) continue;
            $result .= '/' . $segment;
        }
        return Flink_String::from($result)->to_lower();
    }

    public function get_component_file() {
        $content_file = 'vendor/tilokowalski/flink/assets/html/vc' . $this->get_component_name() . '.phtml';
        Flink_Assert::file_exists($content_file, 'missing vc content file: ' . $content_file);
        return $content_file;
    }

    public function render() {
        include $this->get_component_file();
    }

}