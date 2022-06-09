<?php

class Flink_Application {

    private $connection;

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

}
