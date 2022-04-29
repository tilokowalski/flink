<?php

abstract class Flink_EntityMapper {

    private $relation_properties;
    private $datafields;

    abstract static function get_table_name(): string;

    public static function get_instance() {
        $mapper_class = get_called_class();
        return new $mapper_class();
    }

    public function __construct() {
        $this->relation_properties = array();
        $this->define_relation_properties();
        $this->datafields = array();
        $this->define_datafields();
    }

    public function define_relation_properties() {
        return;
    }

    public function define_datafields() {
        return;
    }

    public function add_relation_property(string $attribute, Flink_Database_RelationProperty $property) {
        $this->relation_properties[$attribute] = $property;
    }

    public function add_datafield(string $attribute, Flink_Datafield $datafield) {
        $this->datafields[$attribute] = $datafield;
    }

    public function get_relation_properties() {
        return $this->relation_properties;
    }

    public function get_datafields() {
        return $this->datafields;
    }
    
}
