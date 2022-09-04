<?php

class Flink_ViewComponent_ImageLightbox extends Flink_ViewComponent {

    private $images;

    private $visible_by_default = true;

    public function __construct() {
        parent::__construct('lightbox');
    }

    public function get_images() {
        return $this->images;
    }

    public function get_visible_by_default() {
        return $this->visible_by_default;
    }

    public function add_image(string $src, ?string $title = null) {
        $this->images[] = ['src' => $src, 'title' => $title];
        return $this;
    }

    public function set_visible_by_default(?bool $visible_by_default = true) {
        $this->visible_by_default = $visible_by_default;
    }

}
