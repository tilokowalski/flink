<?php

class Flink_ViewComponent_Toast extends Flink_ViewComponent {

    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_INFO = 'info';

    private $type;
    private $title;
    private $message;

    public function __construct(string $type, string $title, string $message) {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        parent::__construct('toast');
    }

    public function get_type() {
        return $this->type;
    }

    public function get_title() {
        return $this->title;
    }

    public function get_message() {
        return $this->message;
    }

    public static function success($title, $message) {
        (new self(self::TYPE_SUCCESS, $title, $message))->render();
    }

    public static function warning($title, $message) {
        (new self(self::TYPE_WARNING, $title, $message))->render();
    }

    public static function error($title, $message) {
        (new self(self::TYPE_ERROR, $title, $message))->render();
    }

    public static function info($title, $message) {
        (new self(self::TYPE_INFO, $title, $message))->render();
    }
    
}