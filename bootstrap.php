<?php

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
