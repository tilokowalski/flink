<?php

abstract class Flink_Entity {

    public static function get_mapper_class() {
        return get_called_class() . 'Mapper';
    }

    public static function get_list_class() {
        return get_called_class() . 'List';
    }

    public static function get_by(Flink_Database_Predicate $predicate): ?self {
        return self::find_by($predicate)->get_single();
    }

    public static function find_by(Flink_Database_Predicate $predicate): Flink_EntityList {

        $entity_class = get_called_class();
        $list_class = self::get_list_class();
        $result = new $list_class();

        global $connection;

        $response = $connection->fetch("SELECT * FROM " . self::get_mapper_class()::get_table_name() . " WHERE " . $predicate->resolve() . ";");

        foreach ($response as $collation) {
            $entity = new $entity_class();
            foreach ($collation as $attribute => $value) {
                if ($attribute === 'ID') $entity_id = intval($value);
                $type = $connection->fetch("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . self::get_mapper_class()::get_table_name() . "' AND COLUMN_NAME = '" . $attribute . "';")[0]['DATA_TYPE'];
                $entity->$attribute = (new Flink_Database_TypeConverter($type, $value))->convert();
            }
            $result->add($entity);
        }

        return $result;

    }

    public function get_all() {
        return self::find_by_ID(Flink_Database_Predicate::not_null());
    }

    private function get_stringified_attributes() {
        return implode(', ', array_keys(get_object_vars($this)));
    }

    private function get_stringified_values() {
        $result = '';
        foreach (get_object_vars($this) as $value) {
            $result .= '"' . Flink_Database_TypeConverter::stringify($value) . '", ';
        }
        $result = substr($result, 0, -2);
        return $result;
    }

    private function get_stringified_allocations() {
        $result = '';
        foreach (get_object_vars($this) as $attribute => $value) {
            $result .= $attribute . ' = "' . Flink_Database_TypeConverter::stringify($value) . '", ';
        }
        $result = substr($result, 0, -2);
        return $result;
    }
    
    public function save() {
        if (!$this->is_existent()) $this->create();
        $this->update();
    }

    public function is_existent() {
        if (!isset($this->ID)) return false;
        return null !== self::get_by('ID', $this->ID);
    }

    private function create() {
        global $connection;
        $connection->execute("INSERT INTO " . self::get_mapper_class()::get_table_name() . " (" . $this->get_stringified_attributes() . ") VALUES (" . $this->get_stringified_values() . ");");
    }

    private function update() {
        global $connection;
        $connection->execute("UPDATE " . self::get_mapper_class()::get_table_name() . " SET " . $this->get_stringified_allocations() . " WHERE ID = '" . $this->ID . "';");
    }

    public static function __callStatic($function, $parameters) {

        if (method_exists(self::class, $function)) {
            return self::$function($parameters);
        }

        if (strpos($function, 'find_by_') !== false) $actual_function = 'find_by';
        if (strpos($function, 'get_by_') !== false) $actual_function = 'get_by';
        $attribute = str_replace($actual_function . '_', '', $function);

        if (null !== $actual_function && strlen($attribute) > 0) {
            Flink_Assert::equals(1, count($parameters), $actual_function . ' call must be provided with value or predicate');
            $predicate = $parameters[0];
            if (!$predicate instanceof Flink_Database_Predicate) {
                $predicate = Flink_Database_Predicate::equals($predicate);
            }
            $predicate->set_attribute($attribute);
            return self::$actual_function($predicate);
        }

        throw new Flink_Exception_Entity_UndefinedFunction(get_called_class() . '::' . $function . '() is not defined  ');

    }

    public function equals(FLink_Entity $other_entity): bool {
        foreach (get_object_vars($this) as $attribute => $value) {
            if ($other_entity->$attribute !== $value) return false;
        }
        return true;
    }

    public function to_list(): Flink_EntityList {
        $list_class = self::get_list_class();
        $result = new $list_class();
        $result->add($this);
        return $result;
    }

    public function __get($attribute) {

        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }

        $relation_properties = self::get_mapper_class()::get_instance()->get_relation_properties();

        if (array_key_exists($attribute, $relation_properties)) {
            $relation_property = $relation_properties[$attribute];
            return $relation_property->get_content_for_entity($this);
        }

        throw new Flink_Exception_Entity_UnmappedProperty(get_called_class(), $attribute);

    }

}
