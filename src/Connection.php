<?php

class Flink_Connection extends mysqli {

    private $database;

    public function __construct(string $hostname, string $username, string $password, string $database) {
        parent::__construct($hostname, $username, $password, $database);
        $this->database = $database;
    }

    public function fetch(string $query) {
        $result = array();
        $data = $this->query($query);
        if (!$data) throw new Flink_Exception_Database_InvalidQuery($query);
        while ($row = $data->fetch_assoc()) {
            $result []= $row;
        }
        return $result;
    }

    public function execute(string $query) {
        if (!$this->query($query)) {
            throw new Flink_Exception_Database_InvalidQuery($query);
        }
    }

    public function is_first_run() {
        $data = $this->fetch("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '" . $this->database . "';");
        $table_count = intval($data[0]['table_count']);
        return !$table_count > 0;
    }

    public function initialize_from(string $file) {
        $handle = fopen($file, "r");
        if (!$handle) throw new Flink_Exception_Database_InitiationFailed("initiation file '" . $file . "' could not be opened");
        while (($line = fgets($handle)) !== false) {
            $content = Delight_String::from($line)->trim();
            if (Delight_String::from($content)->length() > 0) {
                $this->execute($content);
            }
        }
        fclose($handle);
    }

}
