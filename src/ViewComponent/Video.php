<?php

class Flink_ViewComponent_Video extends Flink_ViewComponent {

    public $src;
    public $timeline;
    public $controls;

    public $autoplay;

    public function __construct(string $src, ?bool $timeline = true, ?bool $controls = true) {
        Flink_Assert::mime_content_type($src, 'video/', 'source file ' . $src . ' is not a playable video');
        $this->src = $src;

        $this->timeline = $timeline;
        $this->controls = $controls;

        $this->autoplay = false;
    }
    
    public function set_timeline($timeline) {
        $this->timeline = $timeline;
        return $this;
    }
    
    public function set_controls($controls) {
        $this->controls = $controls;
        return $this;
    }
    
    public function set_autoplay($autoplay) {
        $this->autoplay = $autoplay;
        return $this;
    }

}