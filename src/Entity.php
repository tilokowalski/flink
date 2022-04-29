<?php

abstract class Flink_Entity {

    public static function get_mapper_class(): string {
        return Flink_String::from(get_called_class())->append('Mapper');
    }

    public static function get_list_class(): string {
        return Flink_String::from(get_called_class())->append('List');
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
                // TODO type conversion takes way too long, probably due to query
                // $type = $connection->fetch("DESCRIBE " . self::get_mapper_class()::get_table_name() . " " . $attribute)[0]['Type'];
                // $entity->$attribute = (new Flink_Database_TypeConverter($type, $value))->convert();
                $entity->$attribute = $value;
            }
            $result->add($entity);
        }

        return $result;

    }

    public function get_all() {
        return self::find_by_ID(Flink_Database_Predicate::not_null());
    }

    private function get_stringified_attributes(): string {
        return implode(', ', array_keys(get_object_vars($this)));
    }

    private function get_stringified_values(): string {
        $result = new Flink_String();
        foreach (get_object_vars($this) as $value) {
            $result = $result->append('"' . Flink_Database_TypeConverter::stringify($value) . '", ');
            $result = Flink_String::from($result);
        }
        return $result->substr(0, -2);
    }

    private function get_stringified_allocations(): string {
        $result = new Flink_String();
        foreach (get_object_vars($this) as $attribute => $value) {
            $result = $result->append($attribute . ' = "' . Flink_Database_TypeConverter::stringify($value) . '", ');
            $result = Flink_String::from($result);
        }
        return $result->substr(0, -2);
    }
    
    public function save() {
        if (!$this->is_existent()) $this->create();
        $this->update();
    }

    public function is_existent() {
        if (!isset($this->ID)) return false;
        return null !== self::get_by_ID($this->ID);
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

        $function = Flink_String::from($function);

        if ($function->contains('find_by_')) $actual_function = 'find_by';
        if ($function->contains('get_by_')) $actual_function = 'get_by';
        $attribute = $function->replace($actual_function . '_', '');

        if (isset($actual_function) && Flink_String::from($attribute)->length() > 0) {
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
