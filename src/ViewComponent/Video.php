<?php

class Flink_ViewComponent_Video extends Flink_ViewComponent {

    private $src;

    private $timelineable;
    private $controlable;
    private $pausable;

    private $autoplay;
    private $muted;

    public function __construct(string $src, ?bool $timelineable = true, ?bool $controlable = true, ?bool $pausable = true, ?bool $autoplay = false, ?bool $muted = false) {
        Flink_Assert::mime_content_type($src, 'video/', 'source file ' . $src . ' is not a playable video');
        $this->src = $src;

        $this->set_timelineable($timelineable);
        $this->set_controlable($controlable);
        $this->set_pausable($pausable);
        $this->set_autoplay($autoplay);
        $this->set_muted($muted);
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

    public function is_pausable() {
        return $this->pausable;
    }

    public function is_autoplay() {
        return $this->autoplay;
    }

    public function is_muted() {
        return $this->muted;
    }

    
    public function set_timelineable(?bool $timelineable = true): self {
        $this->timelineable = $timelineable;
        return $this;
    }
    
    public function set_controlable(?bool $controlable = true): self {
        $this->controlable = $controlable;
        return $this;
    }
    
    public function set_pausable(?bool $pausable = true): self {
        $this->pausable = $pausable;
        return $this;
    }
    
    public function set_autoplay(?bool $autoplay = true): self {
        $this->autoplay = $autoplay;
        return $this;
    }
    
    public function set_muted(?bool $muted = true): self {
        $this->muted = $muted;
        return $this;
    }

}