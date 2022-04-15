<?php

namespace Flink;

class Flink_Connection extends mysqli {

    public function __construct(string $hostname, string $username, string $password, string $database) {
        parent::__construct($hostname, $username, $password, $database);
    }

    public function fetch(string $query) {
        $result = array();
        $data = $this->query($query);
        if (!$data) throw new Flink_Exception_InvalidQuery($query);
        while ($row = $data->fetch_assoc()) {
            $result []= $row;
        }
        return $result;
    }

    public function execute(string $query) {
        return $this->query($query);
    }

}
