<?php

namespace Flink;

use Delight\Assert;

use Flink\Database\Predicate;
use Flink\Exception\Entity\UndefinedFunction;

abstract class EntityList extends \ArrayIterator {

    public static function get_entity_class(): string {
        return str_replace('List', '', get_called_class());
    }

    public function to_array(): array {
        return iterator_to_array($this);
    }

    public function add(?Entity $entity): self {
        if (null === $entity) return $this;
        $entity_class = self::get_entity_class();
        Assert::instanceof($entity, $entity_class, 'failed asserting that entity is of type ' . $entity_class);
        $this []= $entity;
        return $this;
    }

    public function get_single(): ?Entity {
        if (count($this) === 0) return null;
        Assert::equals(count($this), 1, 'failed asserting that list holds only one entity');
        return $this->get_first();
    }

    public function get_first(): ?Entity {
        if (count($this) === 0) return null;
        return $this[0];
    }

    public function limit(int $limit) {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($this as $key => $entity) {
            if (intval($key) === $limit) return $result;
            $result->add($entity);
        }
        return $result;
    }

    public function contains(Entity $other_entity): bool {
        foreach ($this as $entity) {
            if ($entity->equals($other_entity)) return true;
        }
        return false;
    }

    public function exists() {
        return !$this->is_empty();
    }

    public function is_empty() {
        return count($this) === 0;
    }

    public function delete() {
        foreach ($this as $entity) {
            $entity->delete();
        }
    }

    public function without(self $other_list): self {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($this as $entity) {
            if (!$other_list->contains($entity)) {
                $result->add($entity);
            }
        }
        return $result;
    }

    public function intersect(self $other_list): self {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($other_list as $entity) {
            if ($this->contains($entity)) {
                $result->add($entity);
            }
        }
        return $result;
    }

    public function combine_with(self $other_list): self {
        $list_class = self::get_entity_class()::get_list_class();
        $result = new $list_class();
        foreach ($this as $entity) {
            $result->add($entity);
        }
        foreach ($other_list as $other_entity) {
            if (!$result->contains($other_entity)) $result->add($other_entity);
        }
        return $result;
    }

    public function sum(string $attribute) {
        if (count($this) === 0) return 0;
        Assert::is_true(is_numeric($this->get_first()->$attribute), $attribute . ' can not be summed up');
        $result = 0;
        foreach ($this as $entity) {
            $result += $entity->$attribute;
        }
        return $result;
    }

    public function order_by(string $order) {
        return self::find_by_ID(Predicate::not_null(), $order);
    }

    public function __call($function, $parameters) {

        if (method_exists($this, $function)) {
            return $this->$function($parameters);
        }

        if (str_contains($function, 'find_by_')) $actual_function = 'find_by';
        if (str_contains($function, 'get_by_')) $actual_function = 'get_by';
        $attribute = str_replace($actual_function . '_', '', $function);

        if (isset($actual_function) && strlen($attribute) > 0) {
            Assert::greater_equals(count($parameters), 1, $actual_function . ' call must be provided with value or predicate');
            $predicate = $parameters[0];
            if (!$predicate instanceof Predicate) {
                $predicate = Predicate::equals($predicate);
            }
            $predicate->set_attribute($attribute);
            if (count($parameters) > 1) {
                $predicate->set_order($parameters[1]);
            }
            $result = $this->intersect(static::get_entity_class()::find_by($predicate));
            if ($actual_function === 'get_by') return $result->get_single();
            return $result;
        }

        throw new UndefinedFunction(get_called_class() . '->' . $function . '() is not defined  ');

    }
    
}
