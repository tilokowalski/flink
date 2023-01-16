<?php

namespace Flink\Exception\Database;
use mysqli_sql_exception;

class QueryException extends \Flink\Exception\Database {

    public function __construct(mysqli_sql_exception $exception, string $query) {
        parent::__construct('"' . $query . '" ' . $exception->getMessage());
    }
}
