<?php

namespace Flink\Database;

use Delight\Assert;
use Flink\Entity;

class RelationProperty {

    private $entity;
    private $key;
    private $multiple;

    public function __construct(array $data, bool $multiple) {
        $this->set_property_from_data($data, 'entity');
        $this->set_property_from_data($data, 'key');
        $this->multiple = $multiple;
    }

    public static function single(array $data) {
        return new self($data, false);
    }

    public static function multiple(array $data) {
        return new self($data, true);
    }

    private function set_property_from_data(array $data, string $key) {
        Assert::array_key_exists($key, $data, 'failed asserting that key \'' . $key . '\' is defined in relation property data');
        $this->$key = $data[$key];
    }

    public function get_content_for_entity(Entity $entity) {
        $entity_class = $this->entity;
        if ($this->multiple) {
            $function_name = 'find_by_' . $this->key;
            return $entity_class::$function_name($entity->ID);
        } else {
            $key_name = $this->key;
            return $entity_class::get_by_ID($entity->$key_name);
        }
    }

}
