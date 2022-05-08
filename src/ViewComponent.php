<?php

abstract class Flink_ViewComponent {

    private function get_component_name() {
        $component = Flink_String::from(get_called_class())->explode('_');
        return Flink_String::from($component)->to_lower();
    }

    public function get_html() {
        $content_file = '../../assets/html/vc/' . $this->get_component_name . '.phtml';
        Flink_Assert::file_exists($content_file, 'view component can not be rendered without belongig .phtml');
        $content = file_get_contents($content_file);
        foreach (get_object_vars($this) as $attribute) {
            $content = Flink_String::from($content)->replace('{$' . $attribute . '}', $this->$attribute);
        }
        return html_special_chars($content);
    }

    public function __toString() {
        return $this->get_html();
    }

    public function render() {
        echo (string) $this;
    }

}