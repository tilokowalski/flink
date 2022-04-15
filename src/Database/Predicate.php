<?php

namespace Flink\Database;

class Flink_Database_Predicate {

    private $attribute;
    private $operator;
    private $compare;

    const OPERATOR_EQUALS = '=';
    const OPERATOR_NOT_EQUALS = '!=';

    const OPERATOR_LIKE = 'LIKE';
    CONST OPERATOR_NOT_LIKE = 'NOT LIKE';

    const OPERATOR_IS_NULL = 'IS NULL';
    const OPERATOR_IS_NOT_NULL = 'IS NOT NULL';

    public function __construct(string $operator, ?string $compare = null) {
        $this->operator = $operator;
        if (null === $compare) {
            Flink_Assert::in_array($operator, array(self::OPERATOR_IS_NULL, self::OPERATOR_IS_NOT_NULL), 'compare value may not be null for operator \'' . $operator . '\'');
        }
        $this->compare = $compare;
    }

    public static function equals($compare) {
        return new self(static::OPERATOR_EQUALS, $compare);
    }

    public static function not_equals($compare) {
        return new self(static::OPERATOR_NOT_EQUALS, $compare);
    }

    public static function like($compare) {
        return new self(static::OPERATOR_LIKE, $compare);
    }

    public static function not_like($compare) {
        return new self(static::OPERATOR_NOT_LIKE, $compare);
    }

    public static function null() {
        return new self(static::OPERATOR_IS_NULL);
    }

    public static function not_null() {
        return new self(static::OPERATOR_IS_NOT_NULL);
    }

    public function set_attribute(string $attribute) {
        $this->attribute = $attribute;
    }

    public function resolve() {
        switch ($this->operator) {
            case self::OPERATOR_EQUALS:
            case self::OPERATOR_NOT_EQUALS:
            case self::OPERATOR_LIKE:
            case self::OPERATOR_NOT_LIKE:
                return $this->attribute . " " . $this->operator . " '" . $this->compare . "'";
            case self::OPERATOR_IS_NULL:
            case self::OPERATOR_IS_NOT_NULL:
                return $this->attribute . " " . $this->operator;
            default: throw new Flink_Exception_NotImplemented('condition resolution not implemented for operator ' . $this->operator);
        }
    }

}
