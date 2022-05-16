<?php

class Flink_ViewComponent_Video extends Flink_ViewComponent {

    private $src;

    private $timelineable;
    private $controlable;
    private $autoplayable;

    public function __construct(string $src, ?bool $timelineable = true, ?bool $controlable = true) {
        Flink_Assert::mime_content_type($src, 'video/', 'source file ' . $src . ' is not a playable video');
        $this->src = $src;

        $this->set_timelineable($timelineable);
        $this->set_controlable($controlable);
        $this->set_autoplayable(false);
    }


    public function get_src() {
        return $this->src;
    }

    public function is_timelineable() {
        return $this->timelineable;
    }

    public function is_controlable() {
        return $this->controlable;
    }

    public function is_autoplayable() {
        return $this->autoplayable;
    }

    
    public function set_timelineable(?bool $timelineable = true): self {
        $this->timelineable = $timelineable;
        return $this;
    }
    
    public function set_controlable(?bool $controlable = true): self {
        $this->controlable = $controlable;
        return $this;
    }
    
    public function set_autoplayable(?bool $autoplayable = true): self {
        $this->autoplayable = $autoplayable;
        return $this;
    }

}