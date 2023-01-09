<?php

namespace Flink\Database;
use Delight\Exception\NotImplemented;

class TypeConverter {

    private $type;
    private $value;

    const TYPE_INT = 'int';
    const TYPE_VARCHAR = 'varchar';
    const TYPE_TEXT = 'text';
    const TYPE_TINYINT = 'tinyint';
    const TYPE_DATETIME = 'datetime';

    public function __construct(string $type, $value) {
        $this->type = $type;
        $this->value = $value;
    }

    public function convert() {
        switch ($this->type) {
            case static::TYPE_INT: return intval($this->value);
            case static::TYPE_TEXT:
            case static::TYPE_VARCHAR:
                return $this->value;
            case static::TYPE_TINYINT: return (bool) $this->value;
            case static::TYPE_DATETIME: return new \DateTime($this->value);
            default: throw new NotImplemented('database type conversion not implemented for type ' . $this->type);
        }
    }

    public static function stringify($value) {
        if (is_int($value)) return strval($value);
        if (is_bool($value)) return boolval($value) ? '1' : '0';
        if (is_string($value)) return htmlentities($value);
        if ($value instanceof \DateTime) return date_format($value, 'Y.m.d H:i:s');
        throw new NotImplemented('type converter has no implementation for conversion of ' . $value);
    }

}
