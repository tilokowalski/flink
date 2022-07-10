<?php

class Flink_ViewComponent_Toast extends Flink_ViewComponent {

    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_INFO = 'info';

    const POSITION_TOP_LEFT = 'top-left';
    const POSITION_TOP_RIGHT = 'top-right';
    const POSITION_TOP_CENTER = 'top-center';
    const POSITION_BOTTOM_LEFT = 'bottom-left';
    const POSITION_BOTTOM_RIGHT = 'bottom-right';
    const POSITION_BOTTOM_CENTER = 'bottom-center';

    public static $default_position = self::POSITION_TOP_RIGHT;
    public static $default_autoclose = 5000;

    private $type;
    private $title;
    private $message;

    private $position;
    private $autoclose;

    public function __construct(string $type, string $title, string $message) {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
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

    public function get_position() {
        if (null !== $this->position) return $this->position;
        return self::$default_position;
    }

    public function get_autoclose() {
        if (null !== $this->autoclose) return $this->autoclose;
        return self::$default_autoclose;
    }

    public function set_position(string $position) {
        Flink_Assert::in_array($position, [self::POSITION_TOP_LEFT, self::POSITION_TOP_RIGHT, self::POSITION_TOP_CENTER, self::POSITION_BOTTOM_LEFT, self::POSITION_BOTTOM_RIGHT, self::POSITION_BOTTOM_CENTER], $position . ' is not a valid position');
        $this->position = $position;
        return $this;
    }

    public function set_autoclose(int $autoclose) {
        $this->autoclose = $autoclose;
        return $this;
    }

    public function prepare() {
        $_SESSION['toasts'][] = serialize($this);
    }

    public static function prepared_exists() {
        return isset($_SESSION['toasts']) && !empty($_SESSION['toasts']);
    }

    public static function show_prepared() {
        foreach ($_SESSION['toasts'] as $key => $toast) {
            $toast = unserialize($toast);
            $toast->render();
            unset($_SESSION['toasts'][$key]);
        }
    }

    public static function success($title, $message) {
        return new self(self::TYPE_SUCCESS, $title, $message);
    }

    public static function warning($title, $message) {
        return new self(self::TYPE_WARNING, $title, $message);
    }

    public static function error($title, $message) {
        return new self(self::TYPE_ERROR, $title, $message);
    }

    public static function info($title, $message) {
        return new self(self::TYPE_INFO, $title, $message);
    }

}