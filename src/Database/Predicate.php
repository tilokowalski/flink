<?php

class Flink_Database_Predicate {

    private $case_sensitive;

    private $attribute;
    private $operator;
    private $compare;
    private $order;

    const OPERATOR_EQUALS = '=';
    const OPERATOR_NOT_EQUALS = '!=';

    const OPERATOR_LIKE = 'LIKE';
    CONST OPERATOR_NOT_LIKE = 'NOT LIKE';

    const OPERATOR_IS_NULL = 'IS NULL';
    const OPERATOR_IS_NOT_NULL = 'IS NOT NULL';

    const OPERATOR_GREATER = '>';
    const OPERATOR_GREATER_EQUALS = '>=';

    const OPERATOR_LESS = '<';
    const OPERATOR_LESS_EQUALS = '<=';

    public function __construct(string $operator, ?string $compare = null, ?bool $case_sensitive = false, ?string $order = null) {
        $this->operator = $operator;
        if (null === $compare) {
            Delight_Assert::in_array($operator, array(self::OPERATOR_IS_NULL, self::OPERATOR_IS_NOT_NULL), 'compare value may not be null for operator \'' . $operator . '\'');
        }
        $this->compare = $compare;
        $this->case_sensitive = $case_sensitive;
        $this->order = $order;
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

    public static function greater($compare) {
        return new self(static::OPERATOR_GREATER, $compare);
    }

    public static function greater_equals($compare) {
        return new self(static::OPERATOR_GREATER_EQUALS, $compare);
    }

    public static function less($compare) {
        return new self(static::OPERATOR_LESS, $compare);
    }

    public static function less_equals($compare) {
        return new self(static::OPERATOR_LESS_EQUALS, $compare);
    }

    public static function case_sensitive($compare) {
        return new self(static::OPERATOR_EQUALS, $compare, true);
    }

    public function set_attribute(string $attribute) {
        $this->attribute = $attribute;
    }

    public function set_order(string $order) {
        $this->order = $order;
    }

    public function resolve() {
        switch ($this->operator) {
            case self::OPERATOR_EQUALS:
            case self::OPERATOR_NOT_EQUALS:
            case self::OPERATOR_LIKE:
            case self::OPERATOR_NOT_LIKE:
            case self::OPERATOR_GREATER:
            case self::OPERATOR_GREATER_EQUALS:
            case self::OPERATOR_LESS:
            case self::OPERATOR_LESS_EQUALS:
                $result = (($this->case_sensitive) ? 'BINARY ' : '') . $this->attribute . " " . $this->operator . " '" . $this->compare . "'";
                break;
            case self::OPERATOR_IS_NULL:
            case self::OPERATOR_IS_NOT_NULL:
                $result = $this->attribute . " " . $this->operator;
                break;
            default: throw new Delight_Exception_NotImplemented('condition resolution not implemented for operator ' . $this->operator);
        }
        if ($this->order !== null) $result .= " ORDER BY " . $this->order;
        return $result;
    }

}
