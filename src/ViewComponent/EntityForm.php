<?php

class Flink_ViewComponent_EntityForm extends Flink_ViewComponent {

    private $method;
    private $entity;
    private $fields;

    public function __construct(Flink_Entity $entity, ?string $method = 'POST', ?array $fields = null) {
        $this->entity = $entity;
        $this->method = $method;
        if (null === $fields) {
            $fields = array_keys(get_object_vars($this->entity));
            unset($fields[array_search('ID', $fields)]);
        }
        $this->fields = $fields;
    }

    public function is_ready() {
        switch (Flink_String::from($this->method)->to_upper()) {
            case 'POST': return isset($_POST['ready']) && !empty($_POST['ready']);
            default: throw new Flink_Exception_NotImplemented('no implementation for method ' . $this->method);
        }
    }

    public function save() {
        foreach ($this->fields as $field) {
            switch (Flink_String::from($this->method)->to_upper()) {
                case 'POST': $this->entity->$field = $_POST[$field]; break;
                default: throw new Flink_Exception_NotImplemented('no implementation for method ' . $this->method);
            }
        }
        $this->entity->save();
        echo "<meta http-equiv='refresh' content='0'>";
    }

    public function get_html() {
        $result = "<form method='POST'>";
        $result .= "<table>";
        foreach ($this->fields as $field) {
            $result .= "<tr>";
            $result .= "<td><label for='$field'>$field</label></td>";
            $result .= "<td><input type='text' name='$field' id='$field' value='" . $this->entity->$field . "'></td>";
            $result .= "</tr>";
        }
        $result .= "<tr>";
        $result .= "<td><input type='hidden' name='ready' value='1'></td>";
        $result .= "<td><button type='submit'>Speichern</button></td>";
        $result .= "</tr>";
        $result .= "</table>";
        $result .= "</form>";
        return $result;
    }

}
