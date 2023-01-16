<?php

namespace Flink\Exception\Database;
use mysqli_sql_exception;

class QueryException extends \Flink\Exception\Database {

    public function __construct(?string $query = null, mysqli_sql_exception $exception) {
        $message = $exception->getMessage();
        if (null !== $query) $message = 'Query "' . $query . '" failed, ' . $message;
        parent::__construct($message);
    }
}
