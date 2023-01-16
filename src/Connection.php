<?php

namespace Flink;

use Flink\Exception\Database\QueryException;
use mysqli_sql_exception;

class Connection extends \mysqli {

    private $database;

    public function __construct(string $hostname, string $username, string $password, string $database) {
        parent::__construct($hostname, $username, $password, $database);
        $this->database = $database;
    }

    public function fetch(string $query) {
        $result = array();
        try {
            $data = $this->query($query);
            while ($row = $data->fetch_assoc()) {
                $result []= $row;
            }
        } catch (mysqli_sql_exception $e) {
            throw new QueryException($e, $query);
        }
        return $result;
    }

    public function execute(string $query) {
        try {
            $this->query($query);
        } catch (mysqli_sql_exception $e) {
            throw new QueryException($e, $query);
        }
    }

    public function execute_file(string $file) {
        $sql = file_get_contents($file);

        try {
            $this->multi_query($sql);
        } catch (\Exception $e) {
            throw new InvalidQuery($sql);
        }
    }

    public function is_database_empty() {
        $data = $this->fetch("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '" . $this->database . "';");
        $table_count = intval($data[0]['table_count']);
        return !$table_count > 0;
    }

}
