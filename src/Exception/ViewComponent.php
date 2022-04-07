<?php

class Flink_Exception_ViewComponent extends Flink_Exception {

    public function __construct(?string $message = null) {
        if (null === $message) $message = 'failed to handle view component';
        parent::__construct($message);
    }
}
