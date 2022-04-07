<?php

    // ERROR HANDLING CONFIGURATION

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    error_reporting(E_ALL);

    // AUTOMATIC CLASS INCLUSION

    spl_autoload_register('flink_autoload');
    function flink_autoload($class_name) {
        $result = '';
        $segments = explode('_', $class_name);
        foreach ($segments as $key => $segment) {
            if ($key === 0) continue;
            $result .= '/' . ucfirst($segment);
        }
        include 'src' . $result . '.php';
    }