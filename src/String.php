<?php

class Flink_String {

    private $string;

    public function __construct() {
        $this->string = '';
    }

    public static function from(?string $string) {
        $result = new self();
        if (null !== $string) {
            $result->string = $string;
        }
        return $result;
    }

    public function append(string $sequence) {
        return $this->string . $sequence;
    }

    public function length() {
        return strlen($this->string);
    }

    public function contains(string $needle) {
        return strpos($this->string, $needle) !== false;
    }

    public function explode(string $delimiter) {
        return explode($delimiter, $this->string);
    }

    public function substr(int $start, int $length) {
        return substr($this->string, $start, $length);
    }

    public function starts_with(string $needle) {
        return substr($this->string, 0, strlen($needle)) === $needle;
    }

    public function ends_with(string $needle) {
        return substr($this->string, -strlen($needle)) === $needle;
    }

    public function repeat(int $times) {
        return str_repeat($this->string, $times);
    }

    public function replace(string $needle, string $replace) {
        return str_replace($needle, $replace, $this->string);
    }

    public function to_lower() {
        return strtolower($this->string);
    }

    public function to_upper() {
        return strtoupper($this->string);
    }

    public function ucfirst() {
        return ucfirst($this->string);
    }

    public function ucwords() {
        return ucwords($this->string);
    }

    public function trim() {
        return trim($this->string);
    }

    public function utf8_encode() {
        return utf8_encode($this->string);
    }

    public function utf8_decode() {
        return utf8_decode($this->string);
    }

    public function __toString() {
        return $this->get_string();
    }

    public function get_string() {
        return $this->string;
    }

}