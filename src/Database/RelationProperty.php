<?php

class Flink_Database_RelationProperty {

    private $entity_class;
    private $keyname;
    private $list;

    public function __construct(array $data) {
        $this->set_property_from_data($data, 'entity_class');
        $this->set_property_from_data($data, 'keyname');
        $this->set_property_from_data($data, 'list');
    }

    private function set_property_from_data(array $data, string $key) {
        Flink_Assert::array_key_exists($key, $data, 'failed asserting that key \'' . $key . '\' is defined in relation property data');
        $this->$key = $data[$key];
    }

    public function get_content_for_entity(Flink_Entity $entity) {
        $entity_class = $this->entity_class;
        if ($list) {
            $function_name = 'find_by_' . $this->keyname;
            return $entity_class::$function_name($entity->ID);
        } else {
            $key_name = $this->keyname;
            return $entity_class::get_by_ID($entity->$key_name);
        }
    }

}
