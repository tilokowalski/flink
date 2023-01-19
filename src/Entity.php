<?php

namespace Flink;

use Delight\Assert;
use Flink\Database\Predicate;
use Flink\Database\TypeConverter;
use Flink\Exception\Entity\UndefinedFunction;
use Flink\Exception\Entity\UnmappedProperty;

abstract class Entity {

    public static function get_mapper_class(): string {
        return get_called_class() . 'Mapper';
    }

    public static function get_list_class(): string {
        return get_called_class() . 'List';
    }

    public static function get_by(Predicate $predicate): ?self {
        return self::find_by($predicate)->get_single();
    }

    public static function find_by(Predicate $predicate): EntityList {

        $entity_class = get_called_class();
        $list_class = self::get_list_class();
        $result = new $list_class();

        global $connection;

        $response = $connection->fetch("SELECT * FROM " . self::get_mapper_class()::get_table_name() . " WHERE " . $predicate->resolve() . ";");

        foreach ($response as $collation) {
            $entity = new $entity_class();
            foreach ($collation as $attribute => $value) {
                if ($attribute === 'ID') $entity_id = intval($value);
                // TODO type conversion takes way too long, probably due to query
                // $type = $connection->fetch("DESCRIBE " . self::get_mapper_class()::get_table_name() . " " . $attribute)[0]['Type'];
                // $entity->$attribute = (new TypeConverter($type, $value))->convert();
                if ($value == null) {
                    $entity->$attribute = null;
                } else {
                    $entity->$attribute = html_entity_decode($value);
                }
            }
            $result->add($entity);
        }

        return $result;

    }

    public static function get_all() {
        return self::find_by_ID(Predicate::not_null());
    }

    private function get_stringified_attributes(): string {
        $result = "";
        foreach (get_object_vars($this) as $key => $value) {
            if ($value === null) continue;
            $result .= $key . ', ';
        }
        return substr($result, 0, -2);
    }

    private function get_stringified_values(): string {
        $result = "";
        foreach (get_object_vars($this) as $value) {
            if ($value === null) continue;
            $result .= '"' . TypeConverter::stringify($value) . '", ';
        }
        return substr($result, 0, -2);
    }

    private function get_stringified_allocations(): string {
        $result = "";
        foreach (get_object_vars($this) as $attribute => $value) {
            if ($value === null) continue;
            $result .= $attribute . ' = "' . TypeConverter::stringify($value) . '", ';
        }
        return substr($result, 0, -2);
    }
    
    public function save() {
        if (!$this->is_existent()) {
            $this->create();
            return;
        }
        $this->update();
    }

    public function is_existent() {
        if (!isset($this->ID)) return false;
        return null !== self::get_by_ID($this->ID);
    }

    private function create() {
        global $connection;
        $connection->execute("INSERT INTO " . self::get_mapper_class()::get_table_name() . " (" . $this->get_stringified_attributes() . ") VALUES (" . $this->get_stringified_values() . ");");
        $this->ID = $connection->insert_id;
    }

    private function update() {
        global $connection;
        $connection->execute("UPDATE " . self::get_mapper_class()::get_table_name() . " SET " . $this->get_stringified_allocations() . " WHERE ID = '" . $this->ID . "';");
    }

    public function delete() {
        global $connection;
        $connection->execute("DELETE FROM " . self::get_mapper_class()::get_table_name() . " WHERE ID = '" . $this->ID . "';");
    }

    public static function __callStatic($function, $parameters) {

        if (method_exists(self::class, $function)) {
            return self::$function($parameters);
        }

        if (str_contains($function, 'find_by_')) $actual_function = 'find_by';
        if (str_contains($function, 'get_by_')) $actual_function = 'get_by';
        $attribute = str_replace($actual_function . '_', '', $function);

        if (isset($actual_function) && strlen($attribute) > 0) {
            Assert::equals(1, count($parameters), $actual_function . ' call must be provided with value or predicate');
            $predicate = $parameters[0];
            if (!$predicate instanceof Predicate) {
                $predicate = Predicate::equals($predicate);
            }
            $predicate->set_attribute($attribute);
            return self::$actual_function($predicate);
        }

        throw new UndefinedFunction(get_called_class() . '::' . $function . '() is not defined  ');

    }

    public function equals(?self $other_entity): bool {
        if ($other_entity === null) return false;
        foreach (get_object_vars($this) as $attribute => $value) {
            if ($other_entity->$attribute !== $value) return false;
        }
        return true;
    }

    public function to_list(): EntityList {
        $list_class = self::get_list_class();
        $result = new $list_class();
        $result->add($this);
        return $result;
    }

    public function __get($attribute) {
        global $connection;

        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }

        $relation_properties = self::get_mapper_class()::get_instance()->get_relation_properties();

        if (array_key_exists($attribute, $relation_properties)) {
            $relation_property = $relation_properties[$attribute];
            return $relation_property->get_content_for_entity($this);
        }

        // TODO Performantere Lösung für bisher nicht gesetzte Felder, die aber richtig sind
        $result = $connection->fetch("SHOW COLUMNS FROM " . self::get_mapper_class()::get_table_name() . " LIKE '" . $attribute . "';");
        if (count($result) === 1) {
            $this->$attribute = null;
            return;
        }

        throw new UnmappedProperty(get_called_class(), $attribute);

    }

}
