<?php

abstract class Flink_EntityList extends ArrayIterator {

    public static function get_entity_class(): string {
        return str_replace('List', '', get_called_class());
    }

    public function add(Flink_Entity $entity): Flink_EntityList {
        $entity_class = self::get_entity_class();
        Flink_Assert::instanceof($entity, $entity_class, 'failed asserting that entity is of type ' . $entity_class);
        $this []= $entity;
        return $this;
    }

    public function get_single(): ?Flink_Entity {
        if (count($this) === 0) return null;
        Flink_Assert::equals(count($this), 1, 'failed asserting that list holds only one entity');
        return $this->get_first();
    }

    public function get_first(): ?Flink_Entity {
        if (count($this) === 0) return null;
        return $this[0];
    }

    public function contains(Flink_Entity $other_entity): bool {
        foreach ($this as $entity) {
            if ($entity->equals($other_entity)) return true;
        }
        return false;
    }

    public function without(Flink_EntityList $other_list): Flink_EntityList {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($this as $entity) {
            if (!$other_list->contains($entity)) {
                $result->add($entity);
            }
        }
        return $result;
    }

    public function intersect(Flink_EntityList $other_list): Flink_EntityList {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($this as $entity) {
            if ($other_list->contains($entity)) {
                $result->add($entity);
            }
        }
        return $result;
    }

    public function combine_with(Flink_EntityList $other_list): Flink_EntityList {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($this as $entity) {
            $result->add($entity);
        }
        foreach ($other_list as $other_entity) {
            if (!$result->contains($other_entity)) $result->add($entity);
        }
        return $result;
    }

    public function __call($function, $parameters) {

        if (method_exists($this, $function)) {
            return $this->$function($parameters);
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
            $result = $this->intersect(static::get_entity_class()::find_by($predicate));
            if ($actual_function === 'get_by') return $result->get_single();
            return $result;
        }

        throw new Flink_Exception_Entity_UndefinedFunction(get_called_class() . '->' . $function . '() is not defined  ');

    }
    
}
