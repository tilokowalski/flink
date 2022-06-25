<?php

class Flink_Application {

    private $connection;

    private $jquery_included = false;

    public function connect_db(string $hostname, string $username, string $password, string $database) {
        $connection = new Flink_Connection($hostname, $username, $password, $database);
        if ($connection->connect_error) throw new Flink_Exception_Database('connection failed');
        $this->connection = $connection;
        return $this->get_connection();
    }

    public function get_connection() {
        return $this->connection;
    }

    public static function is_localhost() {
        return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
    }
    
    public static function redirect(string $target) {
        echo "<script>window.location = '" . $target . "';</script>";
    }

    public static function set_cookie($name, $value, $expiration, ?string $path = '/') {
        self::include_jquery_if_needed();
        echo "<script>$.cookie('" . $name . "', '" . $value . "', { expires: " . $expiration . ", path: '" . $path . "' })</script>";
    }

    public static function remove_cookie($name, ?string $path = '/') {
        if (!isset($_COOKIE[$name])) return;
        self::include_jquery_if_needed();
        echo "<script>$.removeCookie('" . $name . "', { path: '" . $path . "' })</script>";
    }

    private static function include_jquery_if_needed() {
        if ($this->jquery_included) return;
        echo "<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>";
        echo "<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js'></script>";
        $this->jquery_included = true;
    }

    public static function prepare_url($url) {
        $slashes = '';
        while(!file_exists($slashes . '.htaccess')) $slashes .= '../';
        $result = $slashes . $url;
        return $result;
    }

}
