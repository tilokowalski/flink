<?php

namespace Flink\Exception\Database;
use mysqli_sql_exception;

class QueryException extends \Flink\Exception\Database {

    public function __construct(mysqli_sql_exception $exception, string $query) {
        $message = $exception->getMessage();
        if (null !== $query) $message = 'Query "' . $query . '" failed, ' . $message;
        parent::__construct($message);
    }
}
