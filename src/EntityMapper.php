<?php

namespace Flink;

use Flink\Database\RelationProperty;

abstract class EntityMapper {

    private $relation_properties;

    abstract static function get_table_name(): string;

    public static function get_instance() {
        $mapper_class = get_called_class();
        return new $mapper_class();
    }

    public function __construct() {
        $this->relation_properties = array();
        $this->define_relation_properties();
    }

    public function define_relation_properties() {
        return;
    }

    public function add_relation_property(string $attribute, RelationProperty $property) {
        $this->relation_properties[$attribute] = $property;
    }

    public function get_relation_properties() {
        return $this->relation_properties;
    }
    
}
